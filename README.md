# Budget Tracker

A Bootstrap-based Laravel UI prototype for monthly budgets, daily expenses, categories, and spending reports.

The current version contains UI and mock display data only. Authentication and CRUD backend logic will be added after the interface is finalized.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

The application uses Bootstrap, Bootstrap Icons, jQuery, and ApexCharts from CDN. No Node.js build step is required.
