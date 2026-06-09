# RV WebApp

`rv-webapp` is the new Laravel + MySQL home for the CRM currently implemented in the Avalonia desktop app at `RV-packaging-app`.

The immediate goal of this repo is to recreate the existing CRM workflow on the web with real database-backed persistence, then continue all future product work here.

## Current status

- Laravel 13
- PHP 8.4
- MySQL-backed CRM schema
- Login page plus CRM shell that mirrors the Avalonia app structure
- Database-backed pages for:
  - Dashboard
  - Assign Leads
  - Add Lead workspace shell
  - Lead Details
  - Projects
  - Follow Ups
  - Employees
  - Settings
- Seeded demo CRM data stored in MySQL instead of hard-coded view arrays

## User roles

The app currently seeds these role levels:

- `Admin/Owner`
- `Manager`
- `Agent`

All seeded users use the same password:

```text
12345
```

Example accounts:

- `owner@leadflowcrm.com` — `Admin/Owner`
- `rahul.sharma@leadflowcrm.com` — `Manager`
- `anil.kumar@leadflowcrm.com` — `Manager`
- `neha.verma@leadflowcrm.com` — `Agent`
- `sanjay.patel@leadflowcrm.com` — `Agent`

## Main data model

The current MySQL schema includes:

- `users`
- `leads`
- `projects`
- `follow_ups`
- `lead_notes`
- `lead_documents`

The schema and indexes were designed around the actual list, search, ownership, project tracking, and reminder flows present in the source Avalonia app.

## Source app mapping

The original Avalonia CRM includes these modules:

- Login
- Dashboard
- Add Lead
- Assign Leads
- Lead Details
- Projects
- Follow Ups
- Employees
- Settings

This Laravel app is rebuilding those same flows against a relational backend instead of in-memory view-model state.

## Local setup

### 1. Install dependencies

If Composer is installed globally:

```bash
composer install
```

If you want to use the local Composer binary already bootstrapped in the parent workspace:

```bash
php /Users/salilmomin/Downloads/rv-app/.tools/composer install
```

### 2. Configure the environment

Copy the example environment file:

```bash
cp .env.example .env
```

The example file is already configured for local MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rv_webapp
DB_USERNAME=rv_webapp
DB_PASSWORD=change-me
```

Update the database password and any local settings as needed.

### 3. Generate the app key

```bash
php artisan key:generate
```

### 4. Run migrations and seed CRM data

```bash
php artisan migrate --seed
```

### 5. Start the app

```bash
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000
```

## Testing

The test suite uses Laravel’s testing setup and covers the main route flow plus the CRM schema baseline.

```bash
php artisan test
```

## Seeded data notes

The current UI reads from seeded MySQL records, not from hard-coded page arrays anymore.

That includes:

- lead rows
- project rows
- follow-up rows
- employee rows
- notes and lead documents
- dashboard counts and trend data

## Important migration note

`RV-packaging-app` does not currently use a real database layer. Most of its state is still represented in memory inside Avalonia view models.

That means this Laravel schema is the first real persistence foundation for the CRM, and future work should continue moving business logic here.

## Documents

- Migration audit: `docs/avalonia-migration-audit.md`

## Recommended next steps

1. Add real create/update/delete flows for leads, projects, notes, and follow-ups.
2. Enforce permissions by `Admin/Owner`, `Manager`, and `Agent`.
3. Port the Excel / CSV import workflow into Laravel uploads and jobs.
4. Replace remaining UI placeholders in Add Lead and Settings with full database-backed actions.
5. Expand dashboard reporting with proper SQL-driven analytics.
