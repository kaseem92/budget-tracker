@extends('layouts.app')
@section('title', 'Reports')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush
@section('content')
    @php
        $spent = (float) $monthlyExpenses->sum('amount');
        $remaining = $budget - $spent;
    @endphp
    <div class="page-header">
        <div>
            <h1>Financial Reports</h1>
            <p>Review monthly performance and category-level insights.</p>
        </div>
        <form method="GET" id="reportMonthForm"><input class="form-control month-form" type="month" name="month"
                value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()"></form>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card summary-card">
                <div class="card-body"><small>Monthly Budget</small>
                    <h2>₹{{ number_format($budget, 2) }}</h2><span>{{ $selectedMonth->format('F Y') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card">
                <div class="card-body"><small>Monthly Expense</small>
                    <h2>₹{{ number_format($spent, 2) }}</h2><span>{{ $monthlyExpenses->count() }} transactions</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card">
                <div class="card-body"><small>Remaining</small>
                    <h2 class="{{ $remaining < 0 ? 'text-danger' : 'text-success' }}">₹{{ number_format($remaining, 2) }}
                    </h2><span>{{ $remaining >= 0 ? 'Available balance' : 'Over budget' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Category Summary</h5>
                    <p class="card-subtitle">Expense distribution for {{ $selectedMonth->format('F Y') }}.</p>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Transactions</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categorySummary as $name => $summary)
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td>{{ $summary['count'] }}</td>
                                        <td class="text-end fw-semibold">₹{{ number_format($summary['amount'], 2) }}</td>
                                </tr>@empty<tr>
                                        <td colspan="3" class="empty-state">No data for this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($categorySummary->isNotEmpty())
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th>{{ $monthlyExpenses->count() }}</th>
                                        <th class="text-end">₹{{ number_format($spent, 2) }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Monthly Trend</h5>
                    <p class="card-subtitle">Budget compared with actual spending.</p>
                    <div id="reportTrendChart" class="chart-box"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        $(function() {
            $('#reportMonthForm').validate();
            BudgetCharts.comparison('#reportTrendChart', @json($trend->pluck('label')), @json($trend->pluck('budget')),
                @json($trend->pluck('expense')));
        });
    </script>
@endpush
