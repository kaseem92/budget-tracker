<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    /**
     * Display the reports.
     */
    public function index(): View
    {
        return view('reports.index', [
            'selectedMonth' => Carbon::create(2026, 7, 1),
            'budget' => 65000,
            'monthlyExpenses' => collect(array_fill(0, 18, (object) ['amount' => 2352.78])),
            'categorySummary' => collect([
                'Groceries' => ['count' => 6, 'amount' => 14200],
                'Transport' => ['count' => 4, 'amount' => 8900],
                'Utilities' => ['count' => 3, 'amount' => 7800],
                'Dining' => ['count' => 3, 'amount' => 6100],
                'Healthcare' => ['count' => 2, 'amount' => 5350],
            ]),
            'trend' => collect([
                ['label' => 'Feb', 'budget' => 55000, 'expense' => 44680],
                ['label' => 'Mar', 'budget' => 55000, 'expense' => 51900],
                ['label' => 'Apr', 'budget' => 60000, 'expense' => 49250],
                ['label' => 'May', 'budget' => 58000, 'expense' => 61400],
                ['label' => 'Jun', 'budget' => 60000, 'expense' => 56780],
                ['label' => 'Jul', 'budget' => 65000, 'expense' => 42350],
            ]),
        ]);
    }
}
