# HERVENT ERP

HERVENT ERP is organized as a monorepo with physically separate Laravel and
React applications:

| Path | Purpose |
| --- | --- |
| `backend/` | Laravel backend, API, and business logic |
| `frontend/` | React frontend built with Vite and Tailwind CSS |
| `legacy-reference/` | Read-only legacy UI, behavior, and schema reference |
| `dump/` | Original legacy data; do not modify as part of application work |

Frontend and backend have independent dependency manifests and build output.
The legacy reference remains separate and is not the running application.

## Backend

Run from `backend/`:

```powershell
composer install
php artisan serve
```

Laravel's local environment file is `backend/.env`; keep it private.

## Frontend

Run from `frontend/`:

```powershell
npm install
npm run dev
```

Create the frontend production build with `npm run build`.

See [backend/README.md](./backend/README.md) and
[frontend/README.md](./frontend/README.md) for application-specific notes.
