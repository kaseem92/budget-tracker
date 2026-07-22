<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $selectedMonth = $request->month
            ? Carbon::createFromFormat('Y-m', $request->month)->startOfMonth()
            : now()->startOfMonth();

        $currentMonth = now()->startOfMonth();
        $currentBudget = auth()->user()->budgets()
            ->where('month', $currentMonth->month)
            ->where('year', $currentMonth->year)
            ->first();

        if ($selectedMonth->format('Y-m') === $currentMonth->format('Y-m')) {
            $budgetRecord = $currentBudget;
        } else {
            $budgetRecord = auth()->user()->budgets()
                ->where('month', $selectedMonth->month)
                ->where('year', $selectedMonth->year)
                ->first();
        }

        $expenses = auth()->user()->expenses()
            ->with('category')
            ->whereYear('expense_date', $selectedMonth->year)
            ->whereMonth('expense_date', $selectedMonth->month)
            ->latest('expense_date')
            ->get();

        $budget = (float) ($budgetRecord->amount ?? 0);
        $spent = (float) $expenses->sum('amount');

        $categoryTotals = collect();
        foreach ($expenses as $expense) {
            $categoryName = $expense->category->name;
            $categoryTotals[$categoryName] = ($categoryTotals[$categoryName] ?? 0) + (float) $expense->amount;
        }

        $trendStart = $selectedMonth->copy()->subMonths(5)->startOfMonth();
        $trendExpenses = auth()->user()->expenses()
            ->whereBetween('expense_date', [$trendStart, $selectedMonth->copy()->endOfMonth()])
            ->get(['amount', 'expense_date']);

        $trendTotals = [];
        foreach ($trendExpenses as $expense) {
            $key = $expense->expense_date->format('Y-n');
            $trendTotals[$key] = ($trendTotals[$key] ?? 0) + (float) $expense->amount;
        }

        $trend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = $selectedMonth->copy()->subMonths($i);
            $key = $month->format('Y-n');

            $trend->push([
                'label' => $month->format('M'),
                'total' => $trendTotals[$key] ?? 0,
            ]);
        }

        return view('dashboard', [
            'selectedMonth' => $selectedMonth,
            'currentMonth' => $currentMonth,
            'needsCurrentBudget' => ! $currentBudget,
            'budget' => $budget,
            'spent' => $spent,
            'expenses' => $expenses,
            'categoryTotals' => $categoryTotals,
            'trend' => $trend,
        ]);
    }
}
