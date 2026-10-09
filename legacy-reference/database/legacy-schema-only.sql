-- Schema-only reference extracted from the legacy SQL dump. No INSERT data or production AUTO_INCREMENT values.

-- Select an empty target database before applying this file.

CREATE TABLE `divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(40) NOT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `user_name` varchar(180) DEFAULT NULL,
  `project_name` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_company` varchar(255) DEFAULT NULL,
  `customer_contact` varchar(120) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `lead_type` varchar(50) DEFAULT NULL,
  `pm` varchar(100) DEFAULT NULL,
  `kode_sc` varchar(10) DEFAULT NULL,
  `designer` varchar(180) DEFAULT NULL,
  `color` varchar(180) DEFAULT NULL,
  `packaging` varchar(180) DEFAULT NULL,
  `lead_source` varchar(180) DEFAULT NULL,
  `requirement_text` text DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `po_number` varchar(120) DEFAULT NULL,
  `expedition` varchar(180) DEFAULT NULL,
  `jo_number` varchar(120) DEFAULT NULL,
  `job_order_number` varchar(120) DEFAULT NULL,
  `fa_number` varchar(120) DEFAULT NULL,
  `invoice_dp` varchar(120) DEFAULT NULL,
  `invoice_settlement` varchar(120) DEFAULT NULL,
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `dp_percent` decimal(8,2) NOT NULL DEFAULT 50.00,
  `dp_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `lead_status` enum('Lead','Closed','Cancelled') NOT NULL DEFAULT 'Lead',
  `cancel_reason` text DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `fu_vendor_done` tinyint(1) NOT NULL DEFAULT 0,
  `fu_customer_done` tinyint(1) NOT NULL DEFAULT 0,
  `sequence_no` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lead_custom_processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `process_name` varchar(180) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lead_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `uid` varchar(80) NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `print_type` varchar(180) DEFAULT NULL,
  `product_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `print_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `min_print_qty` int(11) NOT NULL DEFAULT 1,
  `print_price_active` tinyint(1) NOT NULL DEFAULT 1,
  `hv_selling_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `image_path` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `print_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(40) NOT NULL,
  `name` varchar(180) NOT NULL,
  `min_qty` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `production_timeline` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `stage` varchar(180) NOT NULL,
  `status` enum('Belum Mulai','Proses','Selesai') NOT NULL DEFAULT 'Belum Mulai',
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(40) NOT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_code_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `profit_margin` decimal(8,5) NOT NULL DEFAULT 0.30000,
  `selling_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image_path` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(180) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_code` varchar(180) NOT NULL DEFAULT '',
  `hv_code` varchar(180) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(180) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(40) DEFAULT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `movement_type` enum('IN','OUT','ADJUSTMENT','RETURN') NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `movement_date` date DEFAULT NULL,
  `vendor_name` varchar(180) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stock_reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(40) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `kode_sc` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(40) NOT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(180) NOT NULL,
  `contact` varchar(180) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_leads_status` (`lead_status`),
  ADD KEY `idx_leads_created_by` (`created_by`),
  ADD KEY `idx_leads_division` (`division_id`);

ALTER TABLE `lead_custom_processes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_custom_process` (`lead_id`,`process_name`);

ALTER TABLE `lead_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lead_item_uid` (`lead_id`,`uid`),
  ADD KEY `fk_lead_items_product` (`product_id`);

ALTER TABLE `print_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`);

ALTER TABLE `production_timeline`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lead_stage` (`lead_id`,`stage`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_products_name` (`name`),
  ADD KEY `idx_products_division` (`division_id`),
  ADD KEY `fk_products_vendor` (`vendor_id`),
  ADD KEY `fk_products_subcategory` (`subcategory_id`),
  ADD KEY `fk_products_code` (`product_code_id`);

ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `product_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_product_code` (`subcategory_id`,`supplier_code`,`hv_code`);

ALTER TABLE `product_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_subcategory` (`category_id`,`name`);

ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_stock_product` (`product_id`),
  ADD KEY `fk_stock_division` (`division_id`);

ALTER TABLE `stock_reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_reservation` (`lead_id`,`product_id`),
  ADD KEY `fk_reservation_product` (`product_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_division` (`division_id`);

ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_vendor_division` (`division_id`);

ALTER TABLE `divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `lead_custom_processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `lead_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `print_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `production_timeline`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `product_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `product_subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock_reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `leads`
  ADD CONSTRAINT `fk_leads_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

ALTER TABLE `lead_custom_processes`
  ADD CONSTRAINT `fk_custom_process_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

ALTER TABLE `lead_items`
  ADD CONSTRAINT `fk_lead_items_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lead_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

ALTER TABLE `production_timeline`
  ADD CONSTRAINT `fk_timeline_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_code` FOREIGN KEY (`product_code_id`) REFERENCES `product_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `product_subcategories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

ALTER TABLE `product_codes`
  ADD CONSTRAINT `fk_codes_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `product_subcategories` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_subcategories`
  ADD CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

ALTER TABLE `stock_movements`
  ADD CONSTRAINT `fk_stock_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_stock_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

ALTER TABLE `stock_reservations`
  ADD CONSTRAINT `fk_reservation_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservation_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

ALTER TABLE `vendors`
  ADD CONSTRAINT `fk_vendors_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;
