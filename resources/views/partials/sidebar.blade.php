<aside class="sidebar" id="sidebar">
    <nav class="sidebar-nav">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid"></i><span>Dashboard</span></a>
        <div class="nav-heading">Budget Management</div>
        <a class="nav-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}" href="{{ route('budgets.index') }}"><i class="bi bi-wallet2"></i><span>Budgets</span></a>
        <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}"><i class="bi bi-receipt"></i><span>Expenses</span></a>
        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}"><i class="bi bi-tags"></i><span>Categories</span></a>
        <div class="nav-heading">Insights</div>
        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}"><i class="bi bi-bar-chart"></i><span>Reports</span></a>
    </nav>
    <div class="sidebar-help"><i class="bi bi-lightbulb"></i><div><strong>Stay on track</strong><small>Review spending every week.</small></div></div>
</aside>
