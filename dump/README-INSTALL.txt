HERVENT - PAKET SESUAI DATABASE LAMA

Database sumber:
u966833962_erp_hervent.sql

1. Jangan import schema baru jika database lama Anda masih ada.
2. Upload semua file di folder ini ke public_html/ERP/ (atau folder aplikasi).
3. Edit db.php:
   - db_name sudah diisi: u966833962_erp_hervent
   - isi db_user dan db_pass sesuai hPanel.
4. Folder uploads harus writable (755 biasanya cukup).
5. jenis_cetak_api.php otomatis membuat tabel print_type_product_map bila belum ada.
   Anda juga dapat import database/migration_fitur_item_jenis_cetak.sql secara manual.
6. Lakukan Ctrl+F5 setelah upload.

PENTING:
- Login diambil langsung dari tabel users database lama.
- Produk dari products + product_categories + product_subcategories + product_codes.
- Vendor dari vendors.
- Leads dari leads + lead_items + production_timeline + stock_reservations.
- Riwayat tambah stock dari stock_movements.
- Jenis cetak dari print_types.
