<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class ExpenseController extends Controller
{
    /**
     * Display the expenses.
     */
    public function index(): View
    {
        $categories = collect(['Groceries', 'Transport', 'Utilities', 'Dining', 'Healthcare', 'Entertainment'])
            ->map(fn ($name, $index) => (object) ['id' => $index + 1, 'name' => $name]);

        $expenses = collect([
            [1, '2026-07-21', 1, 'Monthly grocery shopping', 6450],
            [2, '2026-07-19', 2, 'Fuel and toll charges', 3200],
            [3, '2026-07-16', 3, 'Electricity bill', 2840],
            [4, '2026-07-12', 4, 'Family dinner', 1950],
            [5, '2026-07-08', 5, 'Medicines and consultation', 2460],
            [6, '2026-07-05', 6, 'Streaming subscriptions', 899],
        ])->map(fn ($item) => (object) [
            'id' => $item[0],
            'expense_date' => Carbon::parse($item[1]),
            'category_id' => $item[2],
            'category' => $categories->firstWhere('id', $item[2]),
            'description' => $item[3],
            'amount' => $item[4],
        ]);

        return view('expenses.index', compact('categories', 'expenses'));
    }
}
