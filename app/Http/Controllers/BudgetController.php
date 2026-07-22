<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class BudgetController extends Controller
{
    /**
     * Display the budgets.
     */
    public function index(): View
    {
        $budgets = collect([
            [1, 7, 2026, 65000],
            [2, 6, 2026, 60000],
            [3, 5, 2026, 58000],
            [4, 4, 2026, 60000],
            [5, 3, 2026, 55000],
            [6, 2, 2026, 55000],
        ])->map(fn ($item) => (object) [
            'id' => $item[0],
            'month' => $item[1],
            'year' => $item[2],
            'amount' => $item[3],
        ]);

        return view('budgets.index', [
            'budgets' => $budgets,
            'expenseTotals' => collect([
                '2026-7' => 42350,
                '2026-6' => 56780,
                '2026-5' => 61400,
                '2026-4' => 49250,
                '2026-3' => 51900,
                '2026-2' => 44680,
            ]),
        ]);
    }
}
