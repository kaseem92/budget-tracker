<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label required-label" for="expense_category_id">Category</label>
        <select class="form-select" id="expense_category_id" name="category_id" required>
            <option value="">Choose category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label required-label" for="expense_amount">Amount</label>
        <div class="input-group">
            <span class="input-group-text">₹</span>
            <input class="form-control" id="expense_amount" name="amount" type="number" step="0.01" min="0.01"
                max="9999999999.99" required>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label required-label" for="expense_date">Expense Date</label>
        <input class="form-control" id="expense_date" name="expense_date" type="date"
            value="{{ now()->format('Y-m-d') }}" required>
    </div>
    <div class="col-12">
        <label class="form-label" for="expense_description">Description</label>
        <textarea class="form-control" id="expense_description" name="description" rows="3" maxlength="1000"></textarea>
    </div>
</div>
