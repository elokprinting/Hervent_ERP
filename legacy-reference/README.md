# HERVENT legacy reference

This folder is a read-only reference bundle for reviewing the former HERVENT
application while planning its Laravel replacement. It is not the new Laravel
application and is not ready to run as-is.

## Contents

- `index.html` — former single-page interface, with its CSS, JavaScript, and
  embedded brand images.
- `api.php`, `product_api.php`, `jenis_cetak_api.php`, and
  `upload_order_image.php` — legacy PHP endpoints, copied for behavior
  reference.
- `db.php.example` — sanitized database connection template. It contains no
  database credentials. The endpoints expect a local `db.php` if someone
  intentionally sets up an isolated legacy test environment.
- `favicon.png` — standalone favicon at the path expected by `index.html`.
- `database/legacy-schema-only.sql` — the 14 legacy table definitions,
  indexes, auto-increment behavior, and foreign-key constraints, without
  database rows or historical auto-increment values.
- `database/migration_fitur_item_jenis_cetak.sql` — the separate legacy
  migration for the print-type/product mapping.
- `legacy-install-notes.txt` and `download-access.htaccess.txt` — original
  installation/access notes retained as historical references. Their hosting
  instructions may be outdated.

## Deliberately not included

- The original SQL dump's `INSERT` data, including user/customer/order records.
- The original `db.php` credentials.
- The 45 files in the legacy `uploads` folder. They are uploaded order images,
  so they may contain customer or business data rather than reusable interface
  assets. The original files remain in `dump/uploads` for separate review.

The original `dump` folder has been left unchanged. Review and sanitize any
legacy data separately before considering a data migration. Do not connect the
legacy endpoints to a production database.

## Schema and workflow notes

The old schema contains tables for divisions, users, leads, lead items,
production timeline, products, product categories/subcategories/codes, print
types, vendors, stock movements, stock reservations, and lead custom processes.
It is a legacy data model, not yet a Laravel migration plan.

The SQL dump's example division records do not match the five cross-division
roles shown in the newer workflow reference. Those records were intentionally
omitted from this bundle; confirm the desired divisions and access rules before
designing the new authorization model.

`index.html` loads Google Fonts, jsPDF, and html2canvas from external CDNs. Some
former order image paths may also point into the excluded `uploads` directory.
The copied `api.php` also references `omzet_monthly`, but that table is not
defined in the supplied SQL dump. Resolve this schema gap before porting that
reporting behavior.
