-- Fitur baru: relasi Item pada Jenis Cetak.
-- Aman untuk database lama. Tidak mengubah data di print_types.
CREATE TABLE IF NOT EXISTS `print_type_product_map` (
  `print_type_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`print_type_id`),
  KEY `idx_ptpm_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
