@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
    @php
        $remaining = $budget - $spent;
        $used = $budget > 0 ? ($spent / $budget) * 100 : 0;
        $progress = min($used, 100);
    @endphp

    <div class="page-header">
        <div>
            <h1>Financial Overview</h1>
            <p>Track your budget, expenses and spending patterns.</p>
        </div>
        <form method="GET" class="month-form" id="dashboardMonthForm">
            <label class="visually-hidden" for="month">Select month</label>
            <input class="form-control" id="month" type="month" name="month"
                value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()">
        </form>
    </div>

    @if ($budget > 0 && $spent > $budget)
        <div class="alert alert-danger" role="alert">
            <div class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Budget Exceeded</div>
            <div class="mt-1">You have exceeded your monthly budget by ₹{{ number_format($spent - $budget, 2) }}.</div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body">
                    <span class="stat-icon primary"><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Monthly Budget</small>
                        <h2>₹{{ number_format($budget, 2) }}</h2>
                        <span>{{ $selectedMonth->format('F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body">
                    <span class="stat-icon warning"><i class="bi bi-receipt"></i></span>
                    <div>
                        <small>Total Expenses</small>
                        <h2>₹{{ number_format($spent, 2) }}</h2>
                        <span>{{ $expenses->count() }} transactions</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body">
                    <span class="stat-icon {{ $remaining >= 0 ? 'success' : 'danger' }}">
                        <i class="bi bi-piggy-bank"></i>
                    </span>
                    <div>
                        <small>Remaining Budget</small>
                        <h2 class="{{ $remaining < 0 ? 'text-danger' : '' }}">
                            {{ $remaining < 0 ? '-₹' . number_format(abs($remaining), 2) : '₹' . number_format($remaining, 2) }}
                        </h2>
                        <span>{{ $remaining >= 0 ? 'Available to spend' : 'Budget exceeded' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body">
                    <span class="stat-icon info"><i class="bi bi-pie-chart"></i></span>
                    <div>
                        <small>Budget Used</small>
                        <h2>{{ number_format($used, 1) }}%</h2>
                        <span>{{ $used < 80 ? 'On track' : 'Watch your spending' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between gap-3 mb-3">
                <div>
                    <h5 class="card-title">Monthly Budget Progress</h5>
                    <p class="card-subtitle mb-0">₹{{ number_format($spent, 2) }} spent out of
                        ₹{{ number_format($budget, 2) }}</p>
                </div>
                <strong class="text-primary">{{ number_format($used, 1) }}%</strong>
            </div>
            <div class="progress budget-progress">
                <div class="progress-bar" style="width: {{ $progress }}%"></div>
            </div>
            <div class="d-flex justify-content-between mt-2 text-muted small">
                <span>₹0</span>
                <span>₹{{ number_format(max($remaining, 0), 2) }} remaining</span>
                <span>₹{{ number_format($budget, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Category Wise Expenses</h5>
                    <p class="card-subtitle">Spending distribution for {{ $selectedMonth->format('F') }}.</p>
                    <div id="categoryExpenseChart" class="chart-box"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Monthly Spending Trend</h5>
                    <p class="card-subtitle">Expenses over the last six months.</p>
                    <div id="expenseTrendChart" class="chart-box"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="section-heading">
                <div>
                    <h5 class="card-title">Recent Expenses</h5>
                    <p class="card-subtitle">Latest transactions for {{ $selectedMonth->format('F Y') }}.</p>
                </div>
                <a href="{{ route('expenses.index', ['month' => $selectedMonth->format('Y-m')]) }}"
                    class="btn btn-outline-primary btn-sm">View all expenses</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses->take(6) as $expense)
                            <tr>
                                <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                <td><span class="category-badge">{{ $expense->category->name }}</span></td>
                                <td>{{ $expense->description ?: '—' }}</td>
                                <td class="text-end fw-semibold">₹{{ number_format($expense->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">No expenses recorded for this month.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@if ($needsCurrentBudget)
    @push('modals')
        <div class="modal fade" id="currentBudgetModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="currentBudgetForm" action="{{ route('budgets.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="month" value="{{ $currentMonth->month }}">
                        <input type="hidden" name="year" value="{{ $currentMonth->year }}">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title">Set Your Monthly Budget</h5>
                                <small class="text-muted">Create a budget for {{ $currentMonth->format('F Y') }} to
                                    continue.</small>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger ajax-errors d-none"></div>
                            <label class="form-label required-label" for="current_budget_amount">Budget Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input class="form-control" id="current_budget_amount" name="amount" type="number"
                                    min="1" max="9999999999.99" step="0.01" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Save Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush
@endif

@push('scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        $(function() {
            $('#dashboardMonthForm').validate();
            BudgetCharts.pie('#categoryExpenseChart', @json($categoryTotals->keys()->values()), @json($categoryTotals->values()));
            BudgetCharts.columns('#expenseTrendChart', @json($trend->pluck('label')), @json($trend->pluck('total')));

            @if ($needsCurrentBudget)
                var budgetModal = new bootstrap.Modal(document.getElementById('currentBudgetModal'));
                budgetModal.show();

                $('#currentBudgetForm').validate({
                    rules: {
                        amount: {
                            required: true,
                            number: true,
                            min: 1,
                            max: 9999999999.99
                        }
                    },
                    errorElement: 'div',
                    errorClass: 'invalid-feedback',
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.closest('.input-group'));
                    },
                    submitHandler: function(form) {
                        var button = $(form).find('button[type="submit"]');
                        var errorBox = $(form).find('.ajax-errors');
                        button.prop('disabled', true);
                        errorBox.addClass('d-none').empty();

                        $.ajax({
                            url: form.action,
                            method: 'POST',
                            data: $(form).serialize()
                        }).done(function(response) {
                            budgetModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Budget saved',
                                text: response.message,
                                timer: 1200,
                                showConfirmButton: false,
                                heightAuto: false
                            }).then(function() {
                                window.location.reload();
                            });
                        }).fail(function(xhr) {
                            var messages = xhr.responseJSON && xhr.responseJSON.errors
                                ? Object.values(xhr.responseJSON.errors).flat()
                                : [xhr.responseJSON?.message || 'Unable to save budget.'];
                            errorBox.html('<ul><li>' + messages.join('</li><li>') + '</li></ul>').removeClass('d-none');
                        }).always(function() {
                            button.prop('disabled', false);
                        });
                    }
                });
            @endif
        });
    </script>
@endpush
