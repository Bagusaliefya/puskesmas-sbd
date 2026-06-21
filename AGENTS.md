# AGENTS.md — Puskesmas SBD

## Tech stack

- **Laravel 13** (PHP 8.3+), SQLite (dev & test), Blade + Tailwind v4 + DaisyUI 5 + Flowbite
- Build: Vite (`@tailwindcss/vite`, `laravel-vite-plugin`)
- Session, cache, queue all backed by database (`config/session.php`, `config/cache.php`, `.env`)
- RBAC: Spatie `laravel-permission` + custom `role` enum column on `users` + custom `RoleMiddleware`

## Commands

| Task | Command |
|---|---|
| Full setup | `composer setup` (installs deps, copies .env, generates key, runs migrations, builds assets) |
| Dev servers | `composer dev` (PHP serve + queue listen + logs + Vite, all via concurrently) |
| Tests | `composer test` (config:clear then php artisan test) |
| Single test | `php artisan test tests/Feature/ExampleTest.php` or `vendor/bin/phpunit` |

## RBAC

Roles: `admin`, `petugas`, `dokter` — defined in `database/seeders/RoleSeeder.php`.
Custom middleware `role` registered in `bootstrap/app.php:16` — uses `auth()->user()->role` enum, not Spatie's native guard.
Routes are guarded by `->middleware('role:admin')`, `->middleware('role:admin,petugas')`, etc. in `routes/web.php`.

Seeder order matters: `RoleSeeder` → `UserSeeder` → `ObatSeeder` → `PegawaiSeeder`. PegawaiSeeder creates petugas & dokter users alongside their profiles.

## Seed credentials (`npm run dev` / `composer dev` after `composer setup`)

- `admin@puskesmas.test` / password (role: admin)
- `petugas@puskesmas.test` / password (role: petugas)
- `dokter@puskesmas.test` / password (role: dokter)

## Architecture

- **Public routes** (no auth): landing, daftar (self-registration → `tipe_pendaftaran = 'mandiri'`)
- **Dashboard** (`/dashboard`, auth required): role-based view per `DashboardController`
- **Admin area**: pegawai, obat CRUD + laporan
- **Petugas area**: pasien CRUD + pendaftaran
- **Dokter area**: pemeriksaan + resep (with detail_resep per-obat line items)
- Models use custom primary keys (`id_pegawai`, `id_pasien`, etc.) defined per-table, not default `id`
- `Pegawai` → single-table parent with optional `Petugas` or `Dokter` extension (hasOne)
- `User` links to `Pegawai` via `id_pegawai` nullable FK

## Testing

- Tests run on SQLite `:memory:`, no `.env` config needed
- `phpunit.xml` configures all test env vars; `RefreshDatabase` trait NOT used in ExampleTest (currently commented out)
- Both `Tests/Unit` (plain PHPUnit) and `Tests/Feature` (Laravel) suites exist

## Style & conventions

- `.editorconfig`: indent 4 spaces, LF line endings
- Indonesian language UI (all labels, messages, flash use Bahasa Indonesia)
- Code in English (PHP, migrations, models)
- Flash messages via `session('success')` / `session('error')` rendered as DaisyUI modals (see `layouts/app.blade.php`)
