<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## System Administrator Access

After migrating the database, grant system administrator access to an existing user from the backend directory:

```bash
php artisan admin:system-access admin@example.com grant
```

The command asks for confirmation before changing access. Use `revoke` to remove it; the last system administrator cannot be revoked. Admin System requires login, the `is_system_admin` authorization flag, and recent password confirmation. The confirmation window uses Laravel's `AUTH_PASSWORD_TIMEOUT` setting (three hours by default). There is no public registration or self-service privilege escalation.

## Lead API

Run `php artisan migrate` before using the Lead endpoints. They use the current authenticated web session and return JSON; write requests must include Laravel's CSRF token.

- `GET /api/leads` lists Leads, paginated by 15.
- `POST /api/leads` creates customer details, a deadline, a PIC user, and one or more product/need items (`product_name`, `quantity`, optional `details`).
- `GET /api/leads/{lead}` returns Lead details and follow-up history.
- `PATCH /api/leads/{lead}` updates supplied Lead fields. Supplying `items` replaces the complete item list.
- `GET /api/leads/{lead}/follow-ups` lists its chronological follow-up history.
- `POST /api/leads/{lead}/follow-ups` requires `notes` and may include `followed_up_at` or Lead fields/items to update. Updated values are recorded with their before/after values in the follow-up.

All authenticated users can currently view and update Leads.

## User Roles

Supported roles follow the legacy role names: `admin`, `manager_sales`, `sales`, `manager_operasional`, `proqc`, `marketing`, `finance`, and `ceo`. Existing users have no role after migration until assigned. Admin Sistem remains a separate full-access privilege and is not an application role.

Assign or clear a user's role from the backend CLI with explicit confirmation. This operational command should only be run by an authorized Admin Sistem operator:

```bash
php artisan admin:user-role sales@example.com sales
php artisan admin:user-role sales@example.com none
```

## Quotation API

Quotation endpoints require an authenticated Sales user or Admin Sistem. Other application roles cannot view, create, update, revise, or mark quotations as sent. Prices and amounts are in IDR, with no discount, tax, or shipping calculations. “Send” records the recipient email, timestamp, and sender; it does not send an email or generate a PDF.

- `GET /api/quotations` lists quotations, paginated by 15.
- `POST /api/leads/{lead}/quotations` creates the first draft for a Lead. Each item requires `product_name`, `quantity`, and `unit_price`; `details` is optional. Subtotal and total are calculated by the backend.
- `GET /api/quotations/{quotation}` returns a quotation and its revision lineage.
- `GET /api/quotations/{quotation}/history` returns every version for the quotation's Lead, ordered by revision, with its items, creator, and sending details.
- `PATCH /api/quotations/{quotation}` updates the latest draft and recalculates totals. Changing unit prices or adding/removing a product creates a new version; the prior draft remains unchanged and is marked `superseded`. Quantity or product details changes without a price change update the latest draft in place. Sent quotations cannot be edited.
- `POST /api/quotations/{quotation}/send` records sending to a required `sent_to_email`. Sending is allowed once per draft.
- `POST /api/quotations/{quotation}/revisions` creates a new draft from the latest sent revision, preserving the prior quotation and its items.

Only the latest draft can be edited or sent. Each generated version records its creator and creation time; earlier versions and their item prices remain available in quotation history.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
