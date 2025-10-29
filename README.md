# Resume Laravel Project

A polished resume/portfolio app built with Laravel, featuring a modern resume page, profile editor, light/dark theme, and an AJAX contact flow.

---

## Features

- Public resume page with skill chips, socials capsules, and section animations
- Edit dashboard with repeaters (Experience, Education, Skills) and keyboard + drag reordering
- Education/Experience “Current” support and year validation (e.g., 2020–Present)
- Theme-aware UI (light/dark), floating Back-to-top, and side navigation with scrollspy
- “Profile Updated” toast after saving (stored via sessionStorage)
- Contact form with AJAX submit, server-side validation, optional attachments, and email delivery
- Email templates and DB persistence of contact messages
- Built with Vite (fast dev + hashed production builds)

## Tech stack

- PHP 8+, Laravel
- MySQL (e.g., via XAMPP)
- Vite for assets (vanilla JS + handcrafted CSS)

## Getting started (Windows / PowerShell)

Prereqs: PHP, Composer, MySQL, Node.js (for Vite), and optionally XAMPP (Apache + MySQL).

1. Install dependencies and create env

    ```powershell
    composer install
    Copy-Item .env.example .env
    php artisan key:generate
    ```

1. Configure database and migrate

    Edit `.env` with DB settings (DB_DATABASE, DB_USERNAME, DB_PASSWORD), then:

    ```powershell
    php artisan migrate
    php artisan storage:link  # expose public storage for uploads
    ```

1. Install and build assets

    ```powershell
    npm install
    npm run dev   # or: npm run build
    ```

    Note (PowerShell execution policy): if `npm run` fails with a policy error, use:

    ```powershell
    cmd /c npm run build
    ```

1. Run the app

    Option A (built-in server):

    ```powershell
    php artisan serve
    ```

    Option B (XAMPP Apache): point DocumentRoot to `public/` and visit <http://localhost/>.

    Default dev URL: <http://127.0.0.1:8000>

## Frontend (Vite)

Assets are referenced in Blade with `@vite`:

```blade
@vite(['resources/css/site.css'])
@vite(['resources/js/theme-auto.js','resources/js/auto-dismiss.js'])
```

- Development (HMR): `npm run dev`
- Production build (hashes to `public/build`): `npm run build`

## Configuration

- Mail (.env):
  - Testing/log mode: `MAIL_MAILER=log` (view messages in `storage/logs/laravel.log`)
  - SMTP mode: `MAIL_MAILER=smtp` + `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`
- Site config: `config/site.php` (some UI/behavior toggles live here)
- Middleware highlights:
  - `EnsureLoggedIn` to protect private routes
  - `NoCache` to prevent sensitive pages from caching

## Key files

- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/*` (Auth, Profile, Resume, Contact)
- Middleware: `app/Http/Middleware/*` (EnsureLoggedIn, NoCache, etc.)
- Models: `app/Models/*` (User, Profile, ContactMessage)
- Mails: `app/Mail/ContactMail.php`
- Views: `resources/views/*` (resume, dashboard, auth, errors, emails)
- Assets: `resources/css/*.css`, `resources/js/*.js`
- Vite config: `vite.config.js`

## Common tasks

```powershell
# Clear cached views/config if assets or views look stale
php artisan view:clear
php artisan config:clear

# Rebuild assets for production
cmd /c npm run build
```

## Troubleshooting

- PowerShell policy blocks npm: run via `cmd /c npm run <script>` or temporarily bypass the policy for the session.
- Assets not updating: `npm run build` (or `npm run dev`) and hard refresh (Ctrl+F5). Also `php artisan view:clear`.
- Blank page after fresh install: check `.env` DB settings and ensure `php artisan key:generate` was run.
- File uploads not accessible: ensure `php artisan storage:link` created the `public/storage` symlink.

## Contributing

Pull requests are welcome for improvements to docs, UX, or tests. For bigger changes, open an issue first to discuss.

## License

This project’s license is not explicitly set. Add a LICENSE file (MIT recommended) if you plan to distribute.
