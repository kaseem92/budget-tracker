@extends('layouts.app')
@section('title', 'Expenses')

@section('content')
    <div class="page-header">
        <div>
            <h1>Expenses</h1>
            <p>Review, filter and manage recorded spending.</p>
        </div>
        <button class="btn btn-primary" id="addExpenseButton" data-bs-toggle="modal" data-bs-target="#expenseModal">
            <i class="bi bi-plus-lg me-1"></i>Add Expense
        </button>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form class="row g-3 align-items-end" id="expenseFilterForm" method="GET">
                <div class="col-lg-5">
                    <label class="form-label" for="expense_search">Search</label>
                    <input class="form-control" id="expense_search" name="search" type="search"
                        value="{{ request('search') }}" placeholder="Search description">
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label" for="filter_category">Category</label>
                    <select class="form-select" id="filter_category" name="category_id">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" for="filter_month">Month</label>
                    <input class="form-control" id="filter_month" name="month" type="month"
                        value="{{ request('month') }}">
                </div>
                <div class="col-lg-2 d-grid">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="section-heading">
                <div>
                    <h5 class="card-title">Expense Records</h5>
                    <p class="card-subtitle">{{ $expenses->total() }} expenses · Total
                        ₹{{ number_format($totalExpenses, 2) }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                <td><span class="category-badge">{{ $expense->category->name }}</span></td>
                                <td>{{ $expense->description ?: '—' }}</td>
                                <td class="fw-semibold">₹{{ number_format($expense->amount, 2) }}</td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-light btn-sm edit-expense" type="button"
                                            data-bs-toggle="modal" data-bs-target="#expenseModal"
                                            data-url="{{ route('expenses.update', $expense->id) }}"
                                            data-category="{{ $expense->category_id }}"
                                            data-amount="{{ $expense->amount }}"
                                            data-date="{{ $expense->expense_date->format('Y-m-d') }}"
                                            data-description="{{ $expense->description }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm text-danger delete-expense" type="button"
                                            data-url="{{ route('expenses.destroy', $expense->id) }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No expenses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $expenses->links() }}
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="expenseModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="expenseForm" action="{{ route('expenses.store') }}" method="POST"
                    data-store-url="{{ route('expenses.store') }}" data-default-date="{{ now()->format('Y-m-d') }}">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">Add Expense</h5>
                            <small class="text-muted">Record a new transaction.</small>
                        </div>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger ajax-errors d-none"></div>
                        @include('expenses.form-modal', ['expense' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="budgetExceededModal" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Budget Limit Exceeded
                    </h5>
                </div>
                <div class="modal-body">
                    <p>Adding this expense will exceed your monthly budget.</p>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Monthly Budget:</span>
                        <strong id="warningBudget"></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Current Expenses:</span>
                        <strong id="warningCurrentExpense"></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>New Expense:</span>
                        <strong id="warningNewExpense"></strong>
                    </div>
                    <div class="alert alert-danger mb-0">
                        You will exceed your budget by <strong id="warningExceededBy"></strong>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" id="cancelExceededExpense" type="button">Cancel</button>
                    <button class="btn btn-danger" id="forceSaveExpense" type="button">Save Anyway</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/expenses.js') }}"></script>
@endpush
