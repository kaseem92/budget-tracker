# Monthly Budget Tracker

Monthly Budget Tracker is a simple Laravel application to manage monthly budgets and daily expenses. It allows users to create monthly budgets, manage expense categories, record expenses, and monitor spending through reports and charts.

## Project Setup

### 1. Clone the project

```bash
git clone <repository-url>
cd budget-tracker
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create the environment file

```bash
cp .env.example .env
```

(Windows)

```bat
copy .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Configure the database

Update your `.env` file according to your database.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=budget_tracker
DB_USERNAME=root
DB_PASSWORD=
```

or use SQLite.

### 6. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 7. Start the project

```bash
php artisan serve
```

Open:

```
http://127.0.0.1:8000
```

---

## Demo Login

```
Email: admin@gmail.com
Password: 123456
```

---

## Features

- User authentication
- Monthly budget management
- Category management
- Expense management
- Dashboard with budget summary
- Monthly spending reports
- Category-wise expense chart
- Monthly expense trend chart
- Recent expenses list
- Budget exceeded warning before saving expense
- AJAX CRUD operations
- Frontend and backend validation

---

## Technologies Used

- Laravel
- PHP
- Blade
- Bootstrap 5
- jQuery
- jQuery Validation Plugin
- ApexCharts
- SweetAlert2
- MySQL

---

## Notes

- Default categories are created by the seeder.
- Users must create the current month's budget after the first login.
- All data is user-specific.