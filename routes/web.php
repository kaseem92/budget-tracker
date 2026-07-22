<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
