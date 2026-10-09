# HERVENT ERP workspace

This workspace contains separate backend and frontend applications:

- `backend/` is the Laravel application. Run Composer and Artisan commands from
  this directory.
- `frontend/` is the React + Vite + Tailwind CSS application. Run npm commands
  from this directory.
- `legacy-reference/` is a read-only behavior and interface reference. Keep its
  existing content intact when implementing the replacement.
- `dump/` contains legacy source data and must not be modified as part of
  application setup.

Keep backend and frontend dependencies, build output, and runtime configuration
inside their respective directories. Do not read, expose, or commit environment
secrets.
