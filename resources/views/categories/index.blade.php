@extends('layouts.app')
@section('title', 'Categories')

@section('content')
    <div class="page-header">
        <div>
            <h1>Expense Categories</h1>
            <p>Organize spending into clear, reusable groups.</p>
        </div>
        <button class="btn btn-primary" id="addCategoryButton" data-bs-toggle="modal"
            data-bs-target="#categoryModal">
            <i class="bi bi-plus-lg me-1"></i>Add Category
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="section-heading">
                <div>
                    <h5 class="card-title">Category Records</h5>
                    <p class="card-subtitle">{{ $categories->total() }} categories available</p>
                </div>
                <input id="categorySearch" type="search" class="form-control table-search"
                    placeholder="Search this page">
            </div>

            <div class="table-responsive">
                <table class="table align-middle" id="categoryTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Expenses</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>
                                    <span class="category-name">
                                        <i class="bi bi-tag" style="color: {{ $category->color ?: '#123e72' }}"></i>
                                        <strong>{{ $category->name }}</strong>
                                    </span>
                                </td>
                                <td>
                                    <span class="color-preview"
                                        style="background-color: {{ $category->color ?: '#123e72' }}"></span>
                                    {{ $category->color ?: 'Default' }}
                                </td>
                                <td>{{ $category->expenses_count }}</td>
                                <td>
                                    <span class="status-badge {{ $category->status ? 'success' : 'muted' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-light btn-sm edit-category" type="button"
                                            data-bs-toggle="modal" data-bs-target="#categoryModal"
                                            data-url="{{ route('categories.update', $category->id) }}"
                                            data-name="{{ $category->name }}"
                                            data-color="{{ $category->color ?: '#123e72' }}"
                                            data-status="{{ $category->status ? 1 : 0 }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm text-danger delete-category" type="button"
                                            data-url="{{ route('categories.destroy', $category->id) }}"
                                            data-name="{{ $category->name }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $categories->links() }}
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="categoryForm" action="{{ route('categories.store') }}" method="POST"
                    data-store-url="{{ route('categories.store') }}">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger ajax-errors d-none"></div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="category_name">Category Name</label>
                            <input class="form-control" id="category_name" name="name" type="text" maxlength="255"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="category_color">Color</label>
                            <input class="form-control form-control-color color-input" id="category_color" name="color"
                                type="color" value="#123e72">
                        </div>
                        <input type="hidden" name="status" value="0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" id="category_status" name="status" type="checkbox"
                                value="1" checked>
                            <label class="form-check-label required-label" for="category_status">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/categories.js') }}"></script>
@endpush
