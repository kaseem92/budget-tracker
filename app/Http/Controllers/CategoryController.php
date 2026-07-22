<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    /**
     * Display the categories.
     */
    public function index(): View
    {
        $categories = collect([
            [1, 'Groceries', 6, true],
            [2, 'Transport', 4, true],
            [3, 'Utilities', 3, true],
            [4, 'Dining', 3, true],
            [5, 'Healthcare', 2, true],
            [6, 'Travel', 0, false],
        ])->map(fn ($item) => (object) [
            'id' => $item[0],
            'name' => $item[1],
            'expenses_count' => $item[2],
            'is_active' => $item[3],
        ]);

        return view('categories.index', compact('categories'));
    }
}
