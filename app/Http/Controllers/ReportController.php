<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $selectedMonth = $request->month
            ? Carbon::createFromFormat('Y-m', $request->month)->startOfMonth()
            : now()->startOfMonth();

        $monthlyExpenses = auth()->user()->expenses()
            ->with('category')
            ->whereYear('expense_date', $selectedMonth->year)
            ->whereMonth('expense_date', $selectedMonth->month)
            ->get();

        $categorySummary = collect();
        foreach ($monthlyExpenses as $expense) {
            $name = $expense->category->name;

            if (! isset($categorySummary[$name])) {
                $categorySummary[$name] = ['count' => 0, 'amount' => 0];
            }

            $summary = $categorySummary[$name];
            $summary['count']++;
            $summary['amount'] += (float) $expense->amount;
            $categorySummary[$name] = $summary;
        }

        $trendStart = $selectedMonth->copy()->subMonths(5)->startOfMonth();
        $trendBudgets = auth()->user()->budgets()
            ->whereBetween('year', [$trendStart->year, $selectedMonth->year])
            ->get();
        $trendExpenses = auth()->user()->expenses()
            ->whereBetween('expense_date', [$trendStart, $selectedMonth->copy()->endOfMonth()])
            ->get(['amount', 'expense_date']);

        $budgetTotals = [];
        foreach ($trendBudgets as $trendBudget) {
            $key = $trendBudget->year.'-'.$trendBudget->month;
            $budgetTotals[$key] = (float) $trendBudget->amount;
        }

        $expenseTotals = [];
        foreach ($trendExpenses as $expense) {
            $key = $expense->expense_date->format('Y-n');
            $expenseTotals[$key] = ($expenseTotals[$key] ?? 0) + (float) $expense->amount;
        }

        $selectedKey = $selectedMonth->format('Y-n');
        $budget = $budgetTotals[$selectedKey] ?? 0;

        $trend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = $selectedMonth->copy()->subMonths($i);
            $key = $month->format('Y-n');

            $trend->push([
                'label' => $month->format('M'),
                'budget' => $budgetTotals[$key] ?? 0,
                'expense' => $expenseTotals[$key] ?? 0,
            ]);
        }

        return view('reports.index', compact(
            'selectedMonth',
            'budget',
            'monthlyExpenses',
            'categorySummary',
            'trend'
        ));
    }
}
