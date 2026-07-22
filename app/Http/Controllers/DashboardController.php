<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $selectedMonth = Carbon::create(2026, 7, 1);
        $expenses = collect([
            ['id' => 1, 'date' => '2026-07-21', 'category' => 'Groceries', 'description' => 'Monthly grocery shopping', 'amount' => 6450],
            ['id' => 2, 'date' => '2026-07-19', 'category' => 'Transport', 'description' => 'Fuel and toll charges', 'amount' => 3200],
            ['id' => 3, 'date' => '2026-07-16', 'category' => 'Utilities', 'description' => 'Electricity bill', 'amount' => 2840],
            ['id' => 4, 'date' => '2026-07-12', 'category' => 'Dining', 'description' => 'Family dinner', 'amount' => 1950],
            ['id' => 5, 'date' => '2026-07-08', 'category' => 'Healthcare', 'description' => 'Medicines and consultation', 'amount' => 2460],
        ])->map(fn ($item) => (object) [
            ...$item,
            'expense_date' => Carbon::parse($item['date']),
            'category' => (object) ['name' => $item['category']],
        ]);

        return view('dashboard', [
            'selectedMonth' => $selectedMonth,
            'budget' => 65000,
            'spent' => $expenses->sum('amount'),
            'expenses' => $expenses,
            'categoryTotals' => collect([
                'Groceries' => 14200,
                'Transport' => 8900,
                'Utilities' => 7800,
                'Dining' => 6100,
                'Healthcare' => 5350,
            ]),
            'trend' => collect([
                ['label' => 'Feb', 'total' => 44680],
                ['label' => 'Mar', 'total' => 51900],
                ['label' => 'Apr', 'total' => 49250],
                ['label' => 'May', 'total' => 61400],
                ['label' => 'Jun', 'total' => 56780],
                ['label' => 'Jul', 'total' => 42350],
            ]),
        ]);
    }
}
