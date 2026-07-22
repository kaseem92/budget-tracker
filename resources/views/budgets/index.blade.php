@extends('layouts.app')
@section('title', 'Budgets')

@section('content')
    <div class="page-header">
        <div>
            <h1>Monthly Budgets</h1>
            <p>Plan spending limits and monitor performance by month.</p>
        </div>
        <button class="btn btn-primary" id="addBudgetButton" data-bs-toggle="modal" data-bs-target="#budgetModal">
            <i class="bi bi-plus-lg me-1"></i>Add Budget
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="section-heading">
                <div>
                    <h5 class="card-title">Budget Records</h5>
                    <p class="card-subtitle">{{ $budgets->total() }} monthly budget records</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Budget</th>
                            <th>Expense</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($budgets as $budget)
                            @php
                                $expense = (float) $expenseTotals->get($budget->year . '-' . $budget->month, 0);
                                $remaining = (float) $budget->amount - $expense;
                                $ratio = $expense / (float) $budget->amount;
                            @endphp
                            <tr>
                                <td class="fw-semibold">
                                    {{ DateTime::createFromFormat('!m', $budget->month)->format('F') }}
                                </td>
                                <td>{{ $budget->year }}</td>
                                <td>₹{{ number_format($budget->amount, 2) }}</td>
                                <td>₹{{ number_format($expense, 2) }}</td>
                                <td class="fw-semibold {{ $remaining < 0 ? 'text-danger' : 'text-success' }}">
                                    ₹{{ number_format($remaining, 2) }}
                                </td>
                                <td>
                                    <span
                                        class="status-badge {{ $ratio > 1 ? 'danger' : ($ratio >= 0.85 ? 'warning' : 'success') }}">
                                        {{ $ratio > 1 ? 'Exceeded' : ($ratio >= 0.85 ? 'Near Limit' : 'On Track') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-light btn-sm edit-budget" type="button"
                                            data-bs-toggle="modal" data-bs-target="#budgetModal"
                                            data-url="{{ route('budgets.update', $budget->id) }}"
                                            data-month="{{ $budget->month }}" data-year="{{ $budget->year }}"
                                            data-amount="{{ $budget->amount }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm text-danger delete-budget" type="button"
                                            data-url="{{ route('budgets.destroy', $budget->id) }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">No budgets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $budgets->links() }}
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="budgetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="budgetForm" action="{{ route('budgets.store') }}" method="POST"
                    data-store-url="{{ route('budgets.store') }}">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">Add Monthly Budget</h5>
                            <small class="text-muted">Set a spending limit for a month.</small>
                        </div>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger ajax-errors d-none"></div>
                        @include('budgets.form-modal', ['budget' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save Budget</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/budgets.js') }}"></script>
@endpush
