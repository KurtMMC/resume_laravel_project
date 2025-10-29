## Project Overview

This repository hosts a simple resume/portfolio site built on Laravel with a modern contact flow:

- Contact form with AJAX submit (no page reload)
- Validation with inline field error display
- Optional file attachments (validated, saved, and emailed)
- Message persistence in the database for record-keeping
- Email delivery via `.env` (log for testing, SMTP for production)

## How it works (Contact Flow)

High-level sequence from the browser to your inbox and DB:

```
[User on resume page]
	|
	| 1) Fills contact form (name, email, phone?, message, attachments)
	| 2) Clicks Send → JS intercepts → fetch() JSON POST
	v
[POST /contact/send] → routes/web.php → ContactController@send
	|
	| Validate inputs (server-side)
	| Save ContactMessage (DB)
	| Store attachments to public disk (storage/app/public/attachments)
	| Send ContactMail (view: resources/views/emails/contact.blade.php) with attachments
	v
[Response]
	- JSON (AJAX): success or field errors (displayed inline)
	- Non-AJAX: redirect back with flash message
```

## Key files

- `routes/web.php` → Route for `POST /contact/send`
- `app/Http/Controllers/ContactController.php` → Validates, persists, uploads files, sends email, returns JSON/redirect
- `app/Models/ContactMessage.php` → Eloquent model; attachments stored as JSON
- `app/Mail/ContactMail.php` → Mailable for composing the contact email
- `resources/views/resume.blade.php` → Contact section + JS for AJAX submission
- `resources/views/emails/contact.blade.php` → Email template (shows details and attachment links)
- `public/css/style.css` → Styles for layout, dark mode, spinners, validation, and login password toggle

## Configuration notes

- Email modes via `.env`:
  - `MAIL_MAILER=log` → writes emails to `storage/logs/laravel.log` (good for testing)
  - `MAIL_MAILER=smtp` → sends real emails (configure host, port, username, password)
- Attachments are stored on the `public` disk under `attachments/` and are served via `/storage/...` after creating the storage symlink.
- Database includes a `contact_messages` table (plus an attachments JSON column) created via migrations.

## Local setup (Windows / PowerShell)

Prereqs: PHP, Composer, MySQL (e.g., XAMPP), and optionally Node.js if you plan to use Vite.

1) Create `.env` and app key

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

2) Configure your database in `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD), then migrate

```powershell
# Standard: run all migrations
php artisan migrate

# If you get "table already exists" errors, run only the contact-related migrations:
php artisan migrate --path=database/migrations/2025_09_23_033843_create_contact_messages_table.php
php artisan migrate --path=database/migrations/2025_09_23_040900_add_attachments_to_contact_messages_table.php
```

3) Expose public storage (for attachments)

```powershell
php artisan storage:link
```

4) Start the dev server

```powershell
php artisan serve
```

5) Optional: install and build frontend assets

```powershell
npm install
npm run dev
```

## Frontend assets with Vite

This project now uses Vite with the Laravel plugin. CSS/JS live under `resources/` and are referenced in Blade with `@vite`:

```
@vite(['resources/css/site.css'])
@vite(['resources/js/theme-auto.js','resources/js/auto-dismiss.js'])
```

- Development (HMR):

```powershell
npm run dev
```

- Production build (outputs to `public/build` with hashed filenames):

```powershell
npm run build
```

Deprecated public files (left as stubs for clarity):

- `public/css/style.css`, `public/css/auth.css`
- `public/js/theme-auto.js`, `public/js/auto-dismiss.js`, `public/js/close-tab.js`

Use the `@vite` examples above instead of linking these directly.

## Try it

1) Open the site (default): http://127.0.0.1:8000

2) Go to the contact section (on the resume page), fill in the form, and submit.

3) Email delivery modes:

- Log mode (recommended for testing):

	In `.env` set `MAIL_MAILER=log`, then refresh config:

	```powershell
	php artisan config:clear
	php artisan config:cache
	```

	Check email contents in `storage/logs/laravel.log`.

- SMTP mode (for real emails):

	Set `MAIL_MAILER=smtp` and configure `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, then refresh config as above.

Attachments will upload to `storage/app/public/attachments` and be accessible under `/storage/attachments/...`.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
