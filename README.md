# Monthly Budget Tracker

Monthly Budget Tracker is a small Laravel application for managing monthly budgets, expense categories, daily expenses, and spending reports. The interface uses Blade and Bootstrap, while create, update, and delete operations use AJAX.

## Features

- User login and logout
- Authenticated user data isolation
- Mandatory current-month budget setup after login
- Monthly budget listing, creation, editing, and deletion
- Category CRUD with color and active/inactive status
- Expense CRUD with category, amount, date, and description
- Expense search, category filter, month filter, and pagination
- Frontend validation using jQuery Validation Plugin
- Backend validation using Laravel `$request->validate()`
- AJAX form submission with success and validation messages
- Budget exceeded confirmation before saving an expense
- "Save Anyway" option when an expense exceeds the monthly budget
- Dashboard cards for budget, total expenses, remaining budget, and usage percentage
- Budget exceeded danger alert and negative remaining balance
- Category-wise expense chart and six-month expense trend
- Recent expenses on the dashboard
- Monthly reports with budget and expense comparison
- Eager loading and bulk queries to avoid N+1 query problems

## Technology

- PHP 8.3 or later
- Laravel (the current `composer.json` uses Laravel `^13.8`)
- Blade
- Bootstrap 5
- Bootstrap Icons
- jQuery
- jQuery Validation Plugin
- ApexCharts
- SweetAlert2
- SQLite by default; MySQL can also be used

Bootstrap, jQuery, Bootstrap Icons, ApexCharts, jQuery Validation, and SweetAlert2 are loaded from CDNs. Node.js and npm are not required for this project.

## Requirements

Install the following before setting up the project:

- PHP 8.3+
- Composer
- PHP extensions required by Laravel
- `pdo_sqlite` for SQLite, or `pdo_mysql` for MySQL
- Git, if cloning the repository

Check the installed versions:

```bash
php -v
composer --version
```

## Installation

### 1. Get the project

```bash
git clone <repository-url>
cd budget-tracker
```

If the project was downloaded as a ZIP file, extract it and open a terminal inside the `budget-tracker` directory.

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create the environment file

Windows Command Prompt:

```bat
copy .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

macOS or Linux:

```bash
cp .env.example .env
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Configure the database

The default `.env.example` uses SQLite:

```env
DB_CONNECTION=sqlite
```

Create the SQLite database file if it does not already exist.

Windows PowerShell:

```powershell
if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File database/database.sqlite }
```

macOS or Linux:

```bash
touch database/database.sqlite
```

### 6. Run migrations and seeders

```bash
php artisan migrate --seed
```

The `--seed` option runs `DatabaseSeeder` immediately after the migrations. It creates the demo user and default expense categories.

### 7. Start the application

```bash
php artisan serve
```

Open the following URL in a browser:

```text
http://127.0.0.1:8000
```

## Demo Login

The database seeder creates this account:

```text
Email: admin@gmail.com
Password: 123456
```

These credentials are for local development and assignment review only.

## First Login Flow

1. Log in using the seeded account.
2. The dashboard checks whether the current month's budget exists.
3. If it does not exist, a mandatory modal asks for the budget amount.
4. Save the current month's budget to continue.
5. Default categories are already available from the seeder.
6. Add expenses from the Expenses page.

If a new expense would exceed the selected month's budget, the application shows a confirmation modal. Cancel leaves the database unchanged. "Save Anyway" submits the expense again with confirmation and saves it.

## Optional MySQL Setup

Create an empty MySQL database, then update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=budget_tracker
DB_USERNAME=root
DB_PASSWORD=
```

After saving `.env`, run:

```bash
php artisan config:clear
php artisan migrate --seed
```

## Useful Commands

Start the local server:

```bash
php artisan serve
```

Run the tests:

```bash
php artisan test
```

Check code formatting without changing files:

Windows:

```bat
vendor\bin\pint --test
```

macOS or Linux:

```bash
./vendor/bin/pint --test
```

Clear cached Laravel files after changing `.env` or configuration:

```bash
php artisan optimize:clear
```

## Project Structure

```text
app/Http/Controllers    Application and CRUD logic
app/Models              Eloquent models and relationships
database/migrations     Database table definitions
database/seeders        Demo user and default category data
public/assets           Application CSS, JavaScript, and images
resources/views         Blade layouts, pages, forms, and modals
routes/web.php          Web and AJAX routes
```

## Notes

- All budget, category, and expense records belong to the authenticated user.
- Expense warnings are calculated using the month of the selected expense date.
- Categories containing expenses cannot be deleted.
- The application uses server-side Laravel validation and client-side jQuery validation.
- No npm build command is required because frontend libraries are loaded from CDNs.
