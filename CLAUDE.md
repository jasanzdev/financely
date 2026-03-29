# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Development
```bash
composer run dev    # Start dev server, queue listener, and Vite watcher concurrently
npm run build       # Production asset build
```

### Testing
```bash
composer test       # Clears config cache, then runs full test suite via Pest PHP
php artisan test --filter=<TestName>   # Run a single test or test class
```

### Code Style
```bash
./vendor/bin/pint   # Format PHP code (Laravel Pint)
```

## Architecture

**Laravel 12 + Livewire 3** application — no separate API or SPA. All interactivity is handled server-side via Livewire components.

### Core Models (all use UUID primary keys, all scoped to `User`)
- **Transaction** — Income or expense record; type enum `income|expense`, state enum `paid|pending`; optional `expected_payment_date` for pending payments
- **Category** — User-defined category with auto-generated slug; cannot be deleted if transactions exist
- **Obligation** — Recurring monthly billing; can auto-generate transactions; toggleable active/inactive

### Livewire Component Pattern
Each feature area has components under `app/Livewire/<Feature>/`:
- `Index` — list view with filtering
- `Create` / `Update` — modal-based forms using Livewire Form Objects (`app/Livewire/Forms/`)
- `Filters` — reactive search/filter panel

Form objects (e.g. `TransactionForm`, `CategoryForm`, `ObligationForm`) use `#[Validate]` attributes and handle both `store()` and `update()` logic.

### Feature Areas
- **Transactions** (`/transaction/*`) — Create, edit, list by month, filter, view pending payments with due-date highlighting
- **Categories** (`/category`) — CRUD with slug management
- **Obligations** (`/obligation`) — Monthly billings that can spawn transactions
- **Dashboard** (`/dashboard`) — Metrics grid (`app/Livewire/Metrics/Grid`)
- **Settings** (`/settings/*`) — Profile, password, appearance, account deletion
- **Auth** (`/routes/auth.php`) — Livewire-based login, register, password reset, email verification

### Frontend
- **Blade templates** in `resources/views/`, with `layouts/app.blade.php` as the main shell
- **Flux** UI component library (pre-built components accessed as `<flux:*>`)
- **Tailwind CSS v4** via Vite plugin
- **flatpickr** for date pickers

### Testing Setup
- Uses **Pest PHP v3**
- In-memory SQLite database (configured in `phpunit.xml`)
- Feature tests live in `tests/Feature/`, unit tests in `tests/Unit/`
- Model factories available for all core models
