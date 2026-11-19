# Order Management

A multi-company web app for managing production/job-work orders, built with **Laravel 10** (PHP 8.1+), Blade, Tailwind CSS and MySQL. It tracks the people who do the work (employees, contractors, masters), the orders given to them, pieces received back, and payments made, plus orders from customers ("parties") with credit/balance tracking.

## Features

- **Two logins**
  - **Super admin** (`/admin`) – manages companies, company users, financial years, and can view employees/items.
  - **Company user** (`/login`) – works inside their own company's data only (every record carries a `company_id`).
- **Employees, Contractors, Masters** – stored in one `employees` table, told apart by type (`EMPLOYEE=1`, `CONTRACTOR=2`, `MASTER=3`).
- **Work orders** per employee/contractor: create, edit, delete, mark pieces **received** (with received-pieces history), record **payments** (with payment history), view totals/balance.
- **Master orders** – item + size + qty + rate with advance/balance and dated payment details.
- **Party (customer) orders** – qty × rate, credit received, balance, payment method, print statements.
- **Catalogue** – items and sizes, per company.
- **Printing** – single-order and full-statement print views.
- **Profile / change password**, soft deletes (`is_deleted`) and restore for companies and users.

## Tech stack

| Layer | Tech |
|---|---|
| Backend | Laravel 10, PHP ^8.1, Laravel Breeze, Sanctum |
| Frontend | Blade, Tailwind CSS, Alpine.js, Vite |
| Database | MySQL (default DB name `ordermanagement`) |
| Images | intervention/image v3 |
| Tests | PHPUnit 10 |

## Project structure

```
app/Http/Controllers/
  Admin/      Super-admin: Company, User, Year, Employee, Item, Dashboard, Auth
  Company/    Company side: Employee, Contractor, Master, Party, Item, Size, Dashboard
  Auth/       Company-user auth (Breeze)
app/Models/   Order, OrderItem, ReceivedItem, MasterOrder, MasterOrderDetail,
              PartyOrder, Party, Employee, Item, Size, Company, Year, Media, User, Admin
app/Helpers/helper.php   Company-scoped lookup helpers (autoloaded)
routes/       web.php, auth.php (company), adminauth.php (admin)
resources/views/  admin, employee, contractor, master, party, item, size, layouts, ...
database/migrations/     Schema history (2023 → 2025)
```

### Main tables

`companies`, `users` (company users), `admins`, `employees`, `orders` → `order_items` / `received_items`, `master_orders` → `master_order_details`, `parties` → `party_orders`, `items`, `sizes`, `years`, `medias`.

## Getting started

Requirements: PHP 8.1+, Composer, Node.js, MySQL (e.g. XAMPP).

```bash
git clone <repo-url> ordermanagement
cd ordermanagement

composer install
npm install

cp .env.example .env
php artisan key:generate
# edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD

php artisan migrate
php artisan storage:link

npm run dev        # or: npm run build
php artisan serve  # or open via XAMPP at http://localhost/.../public
```

Then open:

- Company login: `/login`
- Admin login: `/admin/login`

Public registration is disabled in the routes; create the first admin (and companies/users) via a seeder or `php artisan tinker`.

## Testing

```bash
php artisan test
```

## Notes

- Never commit `.env`; use `.env.example` as the template.
- `/clear-cache` is a public route that clears the cache and routes. Consider removing or protecting it in production.
- Many destructive actions (delete) are `GET` routes; consider moving them to `DELETE`/`POST` for CSRF safety.

## License

MIT (Laravel skeleton).
