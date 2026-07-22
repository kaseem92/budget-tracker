<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer'],
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $categories = auth()->user()->categories()->orderBy('name')->get();
        $query = auth()->user()->expenses()->with('category');

        if ($request->filled('search')) {
            $query->where('description', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('expense_date', $year)->whereMonth('expense_date', $month);
        }

        $totalExpenses = (clone $query)->sum('amount');
        $expenses = $query->latest('expense_date')->paginate(10)->withQueryString();

        return view('expenses.index', compact('categories', 'expenses', 'totalExpenses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id,user_id,'.auth()->id()],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'force_save' => ['nullable', 'boolean'],
        ]);

        $forceSave = $request->boolean('force_save');
        unset($data['force_save']);

        $expenseMonth = Carbon::parse($data['expense_date']);
        $budget = (float) (auth()->user()->budgets()
            ->where('month', $expenseMonth->month)
            ->where('year', $expenseMonth->year)
            ->value('amount') ?? 0);
        $currentExpense = (float) auth()->user()->expenses()
            ->whereYear('expense_date', $expenseMonth->year)
            ->whereMonth('expense_date', $expenseMonth->month)
            ->sum('amount');
        $newExpense = (float) $data['amount'];
        $exceededBy = round(($currentExpense + $newExpense) - $budget, 2);

        if (! $forceSave && $exceededBy > 0) {
            return response()->json([
                'budgetExceeded' => true,
                'budget' => $budget,
                'currentExpense' => $currentExpense,
                'newExpense' => $newExpense,
                'exceededBy' => $exceededBy,
            ]);
        }

        $expense = auth()->user()->expenses()->create($data);

        return response()->json([
            'budgetExceeded' => false,
            'message' => 'Expense created successfully.',
            'expense' => $expense,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $expense = auth()->user()->expenses()->findOrFail($id);

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id,user_id,'.auth()->id()],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $expense->update($data);

        return response()->json([
            'message' => 'Expense updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $expense = auth()->user()->expenses()->findOrFail($id);
        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully.',
        ]);
    }
}
