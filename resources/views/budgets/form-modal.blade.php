<div class="row g-3">
    <div class="col-sm-7">
        <label class="form-label required-label" for="budget_month">Month</label>
        <select class="form-select" id="budget_month" name="month" required>
            <option value="">Choose month</option>
            @foreach (range(1, 12) as $month)
                <option value="{{ $month }}">
                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-5">
        <label class="form-label required-label" for="budget_year">Year</label>
        <input class="form-control" id="budget_year" name="year" type="number" min="2000" max="2100"
            value="{{ now()->year }}" required>
    </div>
    <div class="col-12">
        <label class="form-label required-label" for="budget_amount">Budget Amount</label>
        <div class="input-group">
            <span class="input-group-text">₹</span>
            <input class="form-control" id="budget_amount" name="amount" type="number" step="0.01" min="1"
                max="9999999999.99" required>
        </div>
    </div>
</div>
