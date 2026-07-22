<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = auth()->user()->budgets()
            ->latest('year')
            ->latest('month')
            ->paginate(10);

        $expenseTotals = collect();
        $budgetItems = $budgets->getCollection();

        if ($budgetItems->isNotEmpty()) {
            $latestBudget = $budgetItems->first();
            $oldestBudget = $budgetItems->last();
            $startDate = Carbon::create($oldestBudget->year, $oldestBudget->month)->startOfMonth();
            $endDate = Carbon::create($latestBudget->year, $latestBudget->month)->endOfMonth();

            $expenses = auth()->user()->expenses()
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->get(['amount', 'expense_date']);

            foreach ($expenses as $expense) {
                $key = $expense->expense_date->format('Y-n');
                $expenseTotals[$key] = ($expenseTotals[$key] ?? 0) + (float) $expense->amount;
            }
        }

        return view('budgets.index', compact('budgets', 'expenseTotals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2000,2100'],
            'amount' => ['required', 'numeric', 'min:1', 'max:9999999999.99'],
        ]);

        $exists = auth()->user()->budgets()
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A budget already exists for the selected month.',
            ], 422);
        }

        $budget = auth()->user()->budgets()->create($data);

        return response()->json([
            'message' => 'Budget created successfully.',
            'budget' => $budget,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $budget = auth()->user()->budgets()->findOrFail($id);

        $data = $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2000,2100'],
            'amount' => ['required', 'numeric', 'min:1', 'max:9999999999.99'],
        ]);

        $exists = auth()->user()->budgets()
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->where('id', '!=', $budget->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A budget already exists for the selected month.',
            ], 422);
        }

        $budget->update($data);

        return response()->json([
            'message' => 'Budget updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $budget = auth()->user()->budgets()->findOrFail($id);
        $budget->delete();

        return response()->json([
            'message' => 'Budget deleted successfully.',
        ]);
    }
}
