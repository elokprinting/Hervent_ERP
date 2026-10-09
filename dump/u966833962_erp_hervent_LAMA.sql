-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 01, 2026 at 03:25 AM
-- Server version: 11.8.8-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u966833962_erp_hervent`
--

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'HerVent', 'HV', '2026-08-24 04:25:48', '2026-08-24 04:25:48'),
(2, 'Gudang Flashdisk', 'GF', '2026-08-24 04:25:48', '2026-08-24 04:25:48'),
(3, 'Raya Pro', 'RP', '2026-08-24 04:25:48', '2026-08-24 04:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

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

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `external_id`, `division_id`, `created_by`, `user_name`, `project_name`, `customer_name`, `customer_company`, `customer_contact`, `customer_address`, `customer_email`, `lead_type`, `pm`, `kode_sc`, `designer`, `color`, `packaging`, `lead_source`, `requirement_text`, `order_date`, `deadline`, `po_number`, `expedition`, `jo_number`, `job_order_number`, `fa_number`, `invoice_dp`, `invoice_settlement`, `shipping_cost`, `dp_percent`, `dp_amount`, `subtotal`, `total`, `notes`, `lead_status`, `cancel_reason`, `cancelled_at`, `closed_at`, `fu_vendor_done`, `fu_customer_done`, `sequence_no`, `created_at`, `updated_at`) VALUES
(1, 'ORD001-BPEC', 1, 'azqia', 'azqia', 'an SARAH', 'sarhslm', '', '-', '', '', 'Leads Lama', 'AN', 'AN', '', '', '', 'Shopee', '', '2026-08-24 09:00:00', '2026-08-24 15:33:00', '', '-', 'AN.HV.0001', 'AN.HV.0001', '001', NULL, NULL, 0.00, 0.00, 0.00, 62714.00, 62714.00, '', 'Closed', '', NULL, '2026-08-26 02:08:58', 1, 0, 1, '2026-08-24 08:33:47', '2026-08-28 02:35:20'),
(105, 'ORD003-7MXV', 1, 'shelly', 'shelly', 'CODINGCAMP', 'Pak Hakim', 'DICODING INDONESIA', '+62 877-2209-7942', 'Riseloka Global Mandiri\nM-Square Commercial Suites, Jl. Cibaduyut No.142, Cangkuang Kulon, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40239\n\n\nJl Batik Kumeli No 68 B', '', 'Leads Baru', 'shelly', 'shelly', 'Andi', '', '', 'Referensi', '', '2026-08-27 09:00:00', '2026-09-04 11:56:00', '', '', 'SA.HV.116', 'SA.HV.116', '003', 'SO.2026.08.00003', NULL, 0.00, 50.00, 1325000.00, 2650000.00, 2650000.00, '', 'Closed', '', NULL, '2026-08-27 07:54:19', 0, 0, 3, '2026-08-27 07:53:39', '2026-08-27 07:55:21'),
(120, 'ORD003-563F', 1, 'shelly', 'shelly', 'SUSI AIR', 'Kak Annisa', 'SUSI AIR', '62 811-2003-2127', '', '', 'Leads Lama', 'shelly', 'shelly', 'Andi', '', '', '', '', '2026-08-27 09:00:00', '2026-09-02 15:19:00', '', '', 'SA.HV.0117', 'SA.HV.0117', '003', NULL, NULL, 0.00, 50.00, 4500000.00, 9000000.00, 9000000.00, '', 'Closed', '', NULL, '2026-08-27 08:21:38', 0, 0, 3, '2026-08-27 08:19:43', '2026-08-27 08:21:38');

-- --------------------------------------------------------

--
-- Table structure for table `lead_custom_processes`
--

CREATE TABLE `lead_custom_processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `process_name` varchar(180) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_items`
--

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

--
-- Dumping data for table `lead_items`
--

INSERT INTO `lead_items` (`id`, `lead_id`, `uid`, `product_id`, `product_name`, `quantity`, `print_type`, `product_price`, `print_price`, `min_print_qty`, `print_price_active`, `hv_selling_price`, `line_total`, `image_path`, `sort_order`) VALUES
(226, 1, 'itywyzr5jhlws', 4, 'Flashdisk Compact 16GB', 1, 'Print UV 1 Sisi', 55714.00, 7000.00, 1, 1, 62714.00, 62714.00, 'uploads/orders/order_20260824_083024_6e49472ca85ecd7b.jpg', 0),
(227, 105, 'itylxi7gfxkb', 6, 'Packaging Kertas\nUkuran 18 x 13cm\nBahan Artpaper 310gr', 530, 'Tidak Dicetak', 5000.00, 0.00, 1, 1, 5000.00, 2650000.00, '', 0),
(228, 120, 'itqnbsy4ry5', 12, 'Bucket Hat\nBahan Rafel Denim\nCetak Bordir 1 Titik', 100, '', 90000.00, 0.00, 1, 1, 90000.00, 9000000.00, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `print_types`
--

CREATE TABLE `print_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(40) NOT NULL,
  `name` varchar(180) NOT NULL,
  `min_qty` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `print_types`
--

INSERT INTO `print_types` (`id`, `external_id`, `name`, `min_qty`, `price`, `created_at`, `updated_at`) VALUES
(1, 'CET001-MXV1', 'Print UV 1 Sisi', 1, 7000.00, '2026-08-24 04:58:34', '2026-08-24 04:58:34'),
(6, 'CET002-Q51C', 'Print UV 1 Sisi', 5, 5250.00, '2026-08-24 05:01:10', '2026-08-24 05:01:10'),
(22, 'CET004-Q9QG', 'Print UV 1 Sisi', 10, 4375.00, '2026-08-24 05:01:10', '2026-08-24 05:01:30'),
(23, 'CET005-Q9ZB', 'Print UV 1 Sisi', 30, 3500.00, '2026-08-24 05:01:10', '2026-08-24 05:01:45'),
(24, 'CET006-QA4A', 'Print UV 1 Sisi', 50, 2625.00, '2026-08-24 05:01:10', '2026-08-24 05:01:56'),
(50, 'CET006-RRB2', 'Print UV 1 Sisi', 100, 1750.00, '2026-08-24 05:02:19', '2026-08-24 05:02:19'),
(57, 'CET007-S1ES', 'Print UV 1 Sisi', 1000, 1665.00, '2026-08-24 05:02:32', '2026-08-24 05:02:32'),
(65, 'CET008-HL9M', 'Print UV 2 Sisi', 1, 14000.00, '2026-08-26 02:09:47', '2026-08-26 02:09:47'),
(74, 'CET009-HVEG', 'Print UV 2 Sisi', 5, 10500.00, '2026-08-26 02:10:00', '2026-08-26 02:10:00'),
(84, 'CET010-I685', 'Print UV 2 Sisi', 10, 8750.00, '2026-08-26 02:10:14', '2026-08-26 02:10:14'),
(95, 'CET011-J065', 'Print UV 2 Sisi', 30, 7000.00, '2026-08-26 02:10:53', '2026-08-26 02:10:53'),
(107, 'CET012-K8G8', 'Print UV 2 Sisi', 50, 5250.00, '2026-08-26 02:11:50', '2026-08-26 02:11:50'),
(120, 'CET013-KJYM', 'Print UV 2 Sisi', 100, 3500.00, '2026-08-26 02:12:05', '2026-08-26 02:12:05'),
(134, 'CET014-KWYT', 'Print UV 2 Sisi', 1000, 3325.00, '2026-08-26 02:12:22', '2026-08-26 02:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `production_timeline`
--

CREATE TABLE `production_timeline` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `stage` varchar(180) NOT NULL,
  `status` enum('Belum Mulai','Proses','Selesai') NOT NULL DEFAULT 'Belum Mulai',
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_timeline`
--

INSERT INTO `production_timeline` (`id`, `lead_id`, `stage`, `status`, `sort_order`) VALUES
(460, 1, 'Print UV 1 Sisi', 'Selesai', 0),
(461, 1, 'Packing', 'Selesai', 1),
(462, 1, 'Kirim', 'Selesai', 2),
(463, 105, 'Packing', 'Belum Mulai', 0),
(464, 105, 'Kirim', 'Belum Mulai', 1),
(465, 120, 'Packing', 'Belum Mulai', 0),
(466, 120, 'Kirim', 'Belum Mulai', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

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

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `external_id`, `division_id`, `vendor_id`, `subcategory_id`, `product_code_id`, `name`, `cost_price`, `profit_margin`, `selling_price`, `quantity`, `image_path`, `created_at`, `updated_at`) VALUES
(2, 'PRD001-83C0', 1, 1, 60, 453, 'Flashdisk Compact 4GB', 28500.00, 0.30000, 40714.00, 300, '', '2026-08-24 04:47:01', '2026-08-24 04:47:01'),
(3, 'PRD002-8WP7', 1, 1, 60, 453, 'Flashdisk Compact 8GB', 30500.00, 0.30000, 43571.00, 300, '', '2026-08-24 04:47:39', '2026-08-24 04:47:39'),
(4, 'PRD003-C5Y0', 1, 1, 60, 453, 'Flashdisk Compact 16GB', 39000.00, 0.30000, 55714.00, 299, '', '2026-08-24 04:50:11', '2026-08-24 08:35:18'),
(5, 'PRD004-CQ28', 1, 1, 60, 453, 'Flashdisk Compact 32GB', 50000.00, 0.30000, 71429.00, 300, '', '2026-08-24 04:50:37', '2026-08-24 04:50:37'),
(6, 'PRD005-T9H8', 1, 31, 69, 460, 'Packaging Kertas\nUkuran 18 x 13cm\nBahan Artpaper 310gr', 2500.00, 0.30000, 3571.00, 530, '', '2026-08-27 04:54:30', '2026-08-27 07:53:45'),
(12, 'PRD006-RRUY', 1, 37, 76, 467, 'Bucket Hat\nBahan Rafel Denim\nCetak Bordir 1 Titik', 78000.00, 0.30000, 111429.00, 0, '', '2026-08-27 08:09:18', '2026-08-27 08:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(180) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `created_at`) VALUES
(19, 'Flashdisk', '2026-08-24 04:34:26'),
(28, 'Packaging', '2026-08-27 04:53:53'),
(35, 'Apparel', '2026-08-27 08:08:16');

-- --------------------------------------------------------

--
-- Table structure for table `product_codes`
--

CREATE TABLE `product_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_code` varchar(180) NOT NULL DEFAULT '',
  `hv_code` varchar(180) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_codes`
--

INSERT INTO `product_codes` (`id`, `subcategory_id`, `supplier_code`, `hv_code`, `created_at`) VALUES
(451, 60, 'FDCD04', 'FDCD04', '2026-08-24 04:34:26'),
(453, 60, 'FD-621', 'Compact', '2026-08-24 04:46:19'),
(460, 69, '-', '-', '2026-08-27 04:53:53'),
(467, 76, '-', '-', '2026-08-27 08:08:16'),
(469, 60, 'FDCD11', 'Credence', '2026-08-28 08:30:15'),
(470, 60, 'FDCD12 - Puzzle', 'Cubic', '2026-08-28 08:30:49'),
(471, 60, 'FDCD13', 'Cuare', '2026-08-28 08:31:45'),
(472, 60, 'FDCD14', 'Circle', '2026-08-28 08:32:09'),
(473, 60, 'FDCD15', 'Cosmic', '2026-08-28 08:32:25'),
(474, 83, 'FDPL01', 'Plastech', '2026-08-28 08:35:52'),
(475, 83, 'FDPL11', 'Plastorage', '2026-08-28 08:36:17'),
(476, 83, 'FDSPC02', 'Plascloud', '2026-08-28 08:36:41'),
(477, 83, 'FDSPC25', 'Plasbox', '2026-08-28 08:37:00'),
(478, 83, 'FDPL37', 'Plasport', '2026-08-28 08:37:21'),
(479, 83, 'FDPL38', 'Plasave', '2026-08-28 08:37:52'),
(480, 83, 'FDPL39', 'Plaslink', '2026-08-28 08:38:14'),
(481, 83, 'FDPL40', 'Plassecure', '2026-08-28 08:38:39'),
(482, 83, 'FDPL41', 'Plasstore', '2026-08-28 08:38:59'),
(483, 93, 'FDMT03', 'Metaflex', '2026-08-28 08:39:57'),
(484, 93, 'FDMT15', 'Metacore', '2026-08-28 08:40:18'),
(485, 93, 'FDMT16', 'Metapeak', '2026-08-28 08:40:54'),
(486, 93, 'FDMT17', 'Metakeys', '2026-08-28 08:41:32'),
(487, 93, 'FDMT18', 'Metalink', '2026-08-28 08:41:50'),
(488, 93, 'FDMT19', 'Metaport', '2026-08-28 08:42:11'),
(489, 93, 'FDMT25', 'Metashield', '2026-08-28 08:42:46'),
(490, 93, 'FDMT23', 'Metacircle', '2026-08-28 08:43:13'),
(491, 93, 'FDMT24', 'Metabyte', '2026-08-28 08:43:29'),
(492, 93, 'FDMT26', 'Metapixel', '2026-08-28 08:43:49'),
(493, 93, 'FDMT27', 'Metaforge', '2026-08-28 08:44:15'),
(494, 93, 'FDMT28', 'Metasync', '2026-08-28 08:44:35'),
(495, 106, 'FDLT03', 'Luxor', '2026-08-28 08:46:48'),
(496, 106, 'FDLT20', 'Luminate', '2026-08-28 08:47:04'),
(497, 106, 'FDLT21', 'Lanier', '2026-08-28 08:47:21'),
(498, 106, 'FDLT23', 'Levian', '2026-08-28 08:47:42'),
(499, 106, 'FDLT25', 'Liber', '2026-08-28 08:48:03'),
(500, 106, 'FDLT28', 'Lunar', '2026-08-28 08:48:22'),
(501, 106, 'FDLT26', 'Loyale', '2026-08-28 08:49:06'),
(502, 106, 'FDLT27', 'Lagoon', '2026-08-28 08:50:00'),
(503, 106, 'FDLT29', 'Leon', '2026-08-28 08:51:04'),
(504, 116, 'FDPEN07', 'Plink', '2026-08-28 08:58:31'),
(505, 116, 'FDPEN15', 'Pace', '2026-08-28 08:59:25'),
(506, 116, 'FDPEN16', 'Prayns', '2026-08-28 09:00:14'),
(507, 116, 'FDPEN17', 'Pivot', '2026-08-28 09:00:29'),
(508, 121, 'FDBR01', 'Ruflex 1', '2026-08-28 09:01:26'),
(509, 121, 'FDBR02', 'Ruflex 2', '2026-08-28 09:01:49'),
(510, 124, 'FDWD01', 'Wisp', '2026-08-28 09:02:40'),
(511, 124, 'FDWD02', 'Woodlink', '2026-08-28 09:03:01'),
(512, 124, 'FDWD03', 'Wyldtech', '2026-08-28 09:03:29'),
(513, 124, 'FDWD20', 'Walden', '2026-08-28 09:03:56'),
(514, 124, 'FDWD21', 'Wudwerk', '2026-08-28 09:04:15'),
(515, 130, 'OTGPL01', 'Oxide', '2026-08-28 09:09:21'),
(516, 130, 'OTGMT01', 'Onyx', '2026-08-28 09:09:51'),
(517, 130, 'OTGWD01', 'Orland', '2026-08-28 09:10:12'),
(518, 130, 'OTGCD01', 'Oncard', '2026-08-28 09:10:35'),
(519, 130, 'TPCMT01', 'Orbit', '2026-08-28 09:11:25'),
(520, 130, 'TPCPL01', 'Ontypc', '2026-08-28 09:12:10'),
(521, 130, 'TPCWD01', 'Orion', '2026-08-28 09:12:32'),
(522, 138, 'FDSPC26', 'Crystique', '2026-08-28 09:13:14'),
(523, 138, 'FDSPC31', 'Crysio', '2026-08-28 09:13:33'),
(524, 141, 'FDSPC30', 'Aclear', '2026-08-28 09:14:35'),
(525, 141, 'FDSPC32', 'Arcadia', '2026-08-28 09:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_subcategories`
--

CREATE TABLE `product_subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(180) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_subcategories`
--

INSERT INTO `product_subcategories` (`id`, `category_id`, `name`, `created_at`) VALUES
(60, 19, 'Flashdisk Card', '2026-08-24 04:34:26'),
(69, 28, 'Packaging Kertas', '2026-08-27 04:53:53'),
(76, 35, 'Bucket Hat', '2026-08-27 08:08:16'),
(83, 19, 'Flashdisk Plastik', '2026-08-28 08:34:11'),
(93, 19, 'Flashdisk Metal', '2026-08-28 08:39:35'),
(106, 19, 'Flashdisk Kulit', '2026-08-28 08:46:32'),
(116, 19, 'Flashdisk Pen', '2026-08-28 08:53:04'),
(121, 19, 'Flashdisk Rubber', '2026-08-28 09:01:05'),
(124, 19, 'Flashdisk Kayu', '2026-08-28 09:02:23'),
(130, 19, 'Flashdisk OTG', '2026-08-28 09:08:56'),
(138, 19, 'Flashdisk Kristal', '2026-08-28 09:12:55'),
(141, 19, 'Flashdisk Akrilik', '2026-08-28 09:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `stock_reservations`
--

CREATE TABLE `stock_reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_reservations`
--

INSERT INTO `stock_reservations` (`id`, `lead_id`, `product_id`, `quantity`) VALUES
(216, 1, 4, 1),
(217, 105, 6, 530),
(218, 120, 12, 100);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `external_id`, `username`, `password`, `division_id`, `role`, `jabatan`, `kode_sc`, `created_at`, `updated_at`) VALUES
(1, 'USR001', 'admin', '2026rebound', 1, 'admin', 'admin', 'AD', '2026-08-24 04:25:48', '2026-08-24 04:40:15'),
(5, 'USR002-WYRJ', 'trisna', 'trisnacantikceunah', 1, 'manager_operasional', 'manager_operasional', 'TR', '2026-08-24 04:38:23', '2026-08-24 04:38:23'),
(10, 'USR003-0KAO', 'andi', '2026rebound', 1, 'admin', 'admin', 'AR', '2026-08-24 04:41:10', '2026-08-24 04:41:10'),
(20, 'USR004-I502', 'azqia', 'AzqiaHV', 1, 'manager_sales', 'manager_sales', 'AN', '2026-08-24 04:54:50', '2026-08-24 04:54:50'),
(37, 'USR005-0W5T', 'sugandi', '2026rebound', 1, 'proqc', 'proqc', 'SG', '2026-08-24 09:21:22', '2026-08-24 09:21:22'),
(63, 'USR006-H51O', 'shelly', 'Bismillah', 1, 'sales', 'sales', 'SA', '2026-08-24 09:34:01', '2026-08-24 09:34:01'),
(70, 'USR007-NC5V', 'zulkifli', 'bismillah', 1, 'proqc', 'proqc', 'ZF', '2026-08-24 09:38:50', '2026-08-24 09:38:50'),
(99, 'USR008-VD59', 'azriel', 'azriel14', 1, 'proqc', 'proqc', 'AH', '2026-08-24 09:45:04', '2026-08-24 09:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

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

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `external_id`, `division_id`, `name`, `contact`, `address`, `created_at`, `updated_at`) VALUES
(1, 'VND001-S604', 1, 'KHI', '087886856723', 'Jl. Pluit Karang Molek II Blok P2 selatan No.6, RT.2/RW.8, Pluit, Kec. Penjaringan, Jakarta Utara', '2026-08-24 04:34:38', '2026-08-26 03:47:24'),
(2, 'VND002-0FYZ', 1, 'EGG', '083871250250', 'Jl. Petojo Bar. IV No.1 A, RT.4/RW.4, Petojo Utara, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10130', '2026-08-26 02:24:27', '2026-08-26 02:24:27'),
(4, 'VND003-0IJA', 1, 'RCA (Raja Cover Agenda)', '087822052337', 'blok warna sari, Jl. Cibaduyut Dalam Gg. Aki Udil No.07A, Cibaduyut, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40236', '2026-08-26 03:48:29', '2026-08-26 09:58:51'),
(6, 'VND004-99GA', 1, 'PT Bezzttie Store Indonesia', '087812585000', 'Kp. Gugunungan 01/05 no. 10, Jelekong, Kec. Baleendah, Kabupaten Bandung, Jawa Barat 40375', '2026-08-26 09:59:13', '2026-08-26 09:59:13'),
(7, 'VND005-9YSB', 1, 'Nirwana', '082216948354', 'Jl. Pungkur No.173, Balonggede, Kec. Regol, Kota Bandung, Jawa Barat 40252', '2026-08-26 09:59:45', '2026-08-26 09:59:45'),
(8, 'VND006-AZSX', 1, 'Cibadak 72', '081990585890', 'Jl. Cibadak No.72, Karanganyar, Kec. Astanaanyar, Kota Bandung, Jawa Barat 40241', '2026-08-26 10:00:33', '2026-08-26 10:00:33'),
(9, 'VND007-BQXV', 1, 'Yogi Custom', '085814916796', 'Jalan Cilubang mekar RT 01/08 no. 30 kelurahan Situgede Kota Bogor barat 16115', '2026-08-26 10:01:09', '2026-08-26 10:01:09'),
(10, 'VND008-CQJH', 1, 'Payung Promosi', '083878288270', 'Perumahan grand duta cluster alexandrite blok ax 5 no 16 sangiang gebang raya tangerang', '2026-08-26 10:01:55', '2026-08-26 10:01:55'),
(11, 'VND009-DE09', 1, 'TBI', '081322992864', 'Perumahan Panorama Jatinangor Blok R-32, Ds. Cinanjung, Tanjungsari, Sumedang, Jawa Barat 45362', '2026-08-26 10:02:25', '2026-08-26 10:02:25'),
(12, 'VND010-EJR7', 1, 'Havens & Co.', '085216474291', 'Jln. Cilengkrang 1 No.8 B, RT.02 /RW 01, Cilengkrang Residence', '2026-08-26 10:03:19', '2026-08-26 10:03:19'),
(13, 'VND011-GCBR', 1, 'Kalamator', '085793534587', 'XMHW+W56, RT.01/RW.04, Serangmekar, Kec. Ciparay, Kabupaten Bandung, Jawa Barat 40381', '2026-08-27 02:24:29', '2026-08-27 02:24:29'),
(14, 'VND012-GVKR', 1, 'Teguh Jaya', '087722399488', 'Jl. Cukang Kawung, Alamanda Raya No. 88 RT.3/RW.13 Bandung', '2026-08-27 02:24:54', '2026-08-27 02:24:54'),
(15, 'VND013-F5EU', 1, 'Sumber Printing', '081293220656', 'DHI (Komplek duta harapan indah) Blok N 29 - 30 Rt 07/ Rw 02 Kapuk muara Kecamatan penjaringan Jakarta utara 14460', '2026-08-27 03:47:33', '2026-08-27 03:47:33'),
(16, 'VND014-FUHU', 1, 'Markas Production', '089512449666', 'Jl. Pluto Utara II No.23, Margasari, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286', '2026-08-27 03:48:05', '2026-08-27 03:48:05'),
(17, 'VND015-IDVM', 1, 'CMH', '081234791119', 'Gg dato no 4a, Pasar gelap, Psr pagi lama, Jak bar', '2026-08-27 03:50:03', '2026-08-27 03:50:03'),
(18, 'VND016-J44N', 1, 'PD Gelar', '082118594756', 'Jl. Mahmud No.6, Rahayu, Kec. Margaasih, Kabupaten Bandung, Jawa Barat 40214', '2026-08-27 03:50:37', '2026-08-27 03:50:37'),
(19, 'VND017-JRUM', 1, 'Annisa Toy\'s', '081234955535', 'Jl. Sayati Hilir Dalam, RT.01 rw08, Sayati, Kec. Margahayu, Kabupaten Bandung, Jawa Barat 40228', '2026-08-27 03:51:08', '2026-08-27 03:51:08'),
(20, 'VND018-KMRU', 1, 'EProduction', '085720331939', 'Jl. Curug Candung dalam No.14C, Mekarwangi, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40237', '2026-08-27 03:51:48', '2026-08-27 03:51:48'),
(21, 'VND019-LAVW', 1, 'Alifa Azkiya Store', '085222939882', 'depan mesjid al hidayah, Jalan Babakan Sari No.03 Babakan Sari Kecamatan Kiaracondong belakang griya masuk, Gg. Temon, Babakan Sari, Kec. Kiaracondong, Kota Bandung, Jawa Barat 40283', '2026-08-27 03:52:19', '2026-08-27 03:52:19'),
(22, 'VND020-M879', 1, 'PT Kedawung Subur Surabaya', '088217751888', 'Jl. Raya Kalirungkut, Kali Rungkut, Kec. Rungkut, Surabaya, Jawa Timur 60293', '2026-08-27 03:53:02', '2026-08-27 03:53:02'),
(23, 'VND021-MYXD', 1, 'Botol Kosmetik Import', '081291901108', 'jalan sunter muara baru blok A no 1A sunter agung jakarta utara (gerbang warna biru)', '2026-08-27 03:53:37', '2026-08-27 03:53:37'),
(24, 'VND022-OPIR', 1, 'Baju Kilat', '082258858852', 'Jl. Plered I No.17, Antapani Tengah, Kec. Antapani, Kota Bandung, Jawa Barat 40291', '2026-08-27 03:54:59', '2026-08-27 03:54:59'),
(25, 'VND023-PXZF', 1, 'Muara Laser Bandung', '081394070704 / 087896704680', 'Jl. Inhoftank No.116, Kb. Lega, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40235', '2026-08-27 03:55:56', '2026-08-27 03:55:56'),
(26, 'VND024-S3S5', 1, 'Ujang Medali', '082121008345', 'Jl pasawahan rt04 rw11 desa sayati gg h jarkasih dekat pabrik gracee hil', '2026-08-27 03:57:37', '2026-08-27 03:57:37'),
(27, 'VND025-SRFM', 1, 'Gendis Konveksi', '082297802491', 'Jl. Sukaasih IV No.39, RT.05/RW.07, Sindang Jaya, Kec. Mandalajati, Kota Bandung, Jawa Barat 40195', '2026-08-27 03:58:07', '2026-08-27 03:58:07'),
(28, 'VND026-XLDN', 1, 'Gudang Plastik Bandung', '081214721676', 'Jl. Uranus Utama No.26, RT.008/RW.006, Sekejati, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286', '2026-08-27 04:01:53', '2026-08-27 04:01:53'),
(29, 'VND027-FUHN', 1, 'Pinboo', '081320620692', 'Gg. Gagak I No.19, Sukaluyu, Kec. Cibeunying Kaler, Kota Bandung, Jawa Barat 40123', '2026-08-27 04:44:05', '2026-08-27 04:44:05'),
(30, 'VND028-GGFL', 1, 'Banteng Print', '089528965731', 'Jl. Buah Batu No.31, Burangrang, Kec. Lengkong, Kota Bandung, Jawa Barat 40262', '2026-08-27 04:44:32', '2026-08-27 04:44:32'),
(31, 'VND029-H54M', 1, 'Modern Offset', '081299669076', 'Jl. Jend. sudirman No. 102 Bandung', '2026-08-27 04:45:04', '2026-08-27 04:45:04'),
(32, 'VND030-HR0N', 1, 'Woodka', '081312345385', 'Jl. Tubagus Ismail V No.14, Sekeloa, Kecamatan Coblong, Kota Bandung, Jawa Barat 40134', '2026-08-27 04:45:33', '2026-08-27 04:45:33'),
(33, 'VND031-II3D', 1, 'Kardus Bandung', '081322359388', '009, Jl. Pasir Salam No.21, RT.006/RW.10 - Kel, Ancol, Kec. Regol, Kota Bandung, Jawa Barat 40254', '2026-08-27 04:46:08', '2026-08-27 04:46:08'),
(34, 'VND032-JV1G', 1, 'Grandsuka', '085230991082', 'Nancy Store . ( Sebelah Rm.Dapur Mama Ita.).Panam, jalan Delima,  KOTA PEKANBARU, TAMPAN, RIAU, ID, 28294', '2026-08-27 04:47:12', '2026-08-27 04:47:12'),
(35, 'VND033-L41T', 1, 'Dede Rubber', '089676956236', 'kp Bojong Emas, RT./rw/RW.03/05, Bojongemas, Kec. Solokanjeruk, Kabupaten Bandung, Jawa Barat 40376', '2026-08-27 04:48:10', '2026-08-27 04:48:10'),
(36, 'VND034-M1K8', 1, 'XMat Cibadak', '089678633090', 'Jl. Cibadak No.62, Karanganyar, Kec. Astanaanyar, Kota Bandung, Jawa Barat 40241', '2026-08-27 04:48:53', '2026-08-27 04:48:53'),
(37, 'VND035-PGKH', 1, 'Konveksi Topi Algifari', '085723654864', 'Jl. Satria Wetan No.A8, Margahayu Utara, Kec. Babakan Ciparay, Kota Bandung, Jawa Barat 40224', '2026-08-27 08:07:30', '2026-08-27 08:07:30'),
(38, 'VND036-UYLI', 1, 'Istana Payung Jakarta', '081284407382', '1, Jl. Perniagaan Timur No.24 A, RT.1/RW.1, Roa Malaka, Kec. Tambora, Jakarta, Daerah Khusus Ibukota Jakarta 11230', '2026-08-28 02:51:32', '2026-08-28 02:51:32'),
(39, 'VND037-VVXT', 1, 'Algo Tas Seminar', '087825955538', 'Jl. Sadang No.90, RT.05/RW.08, Margahayu Tengah, Kec. Margahayu, Kabupaten Bandung, Jawa Barat 40225', '2026-08-28 02:52:15', '2026-08-28 02:52:15'),
(40, 'VND038-1YXM', 1, 'CV Rafi Mandiri', '082126156999', 'Cibaduyut Kidul, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40227', '2026-08-28 02:57:25', '2026-08-28 02:57:25'),
(53, 'VND039-8V0D', 1, 'GPP (Gudang Pena Promosi)', '08992364024', 'Jalan Perniagaan Barat II no11F, RT.3/RW.2, Roa Malaka, Tambora, West Jakarta City, Jakarta 11230', '2026-08-28 03:30:20', '2026-08-28 03:30:20'),
(54, 'VND040-B27M', 1, 'Pabrik Jam', '08164826658', 'Jln. 1 maret  no. 68 kamal. Kalideres', '2026-08-28 03:32:08', '2026-08-28 03:32:08'),
(55, 'VND041-BUNT', 1, 'Ihsan Topi Bandung', '081321025062', 'Curug RT.01/RW.08, Rahayu, Margaasih', '2026-08-28 03:32:39', '2026-08-28 03:32:39'),
(56, 'VND042-CDTH', 1, 'Bonekaku', '085774511090', 'Jl Bogor Bekasi no 41, Ciketing Udik, Bantar Gebang, Bekasi', '2026-08-28 03:33:04', '2026-08-28 03:33:04'),
(57, 'VND043-VDCC', 1, 'Kezka Printing', '0811214699', 'Jl. Kawaluyaan Indah Raya No.Ruko 5A, Jatisari, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286', '2026-08-28 07:59:47', '2026-08-28 07:59:47'),
(58, 'VND044-WP5D', 1, 'Linken Store', '081297370252', 'CV Selamat Mandi Kaca\nJl. satria 1 No. 15A, Pademangan Barat, jakarta Utara 14420', '2026-08-28 08:00:49', '2026-08-28 08:00:49'),
(59, 'VND045-XC4X', 1, 'Rajas Konveksi', '087875544611', 'Jl Lemah neundeut 01 sukawarna..bdg', '2026-08-28 08:01:18', '2026-08-28 08:01:18'),
(60, 'VND046-Y95H', 1, 'Agen Handuk SH Grosir', '087722580905', 'Komplek Taman Cileunyi, Jl. Taman Utama II No.12 Blok 2-E, Cileunyi Kulon, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40622', '2026-08-28 08:02:01', '2026-08-28 08:02:01'),
(61, 'VND047-YYRT', 1, 'Ruzpack Official Store', '081311494786', 'Jl. Pangeran Jayakarta Dalam No.42 Vi, RT.7/RW.7, Mangga Dua Sel., Kecamatan Sawah Besar, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10730', '2026-08-28 08:02:34', '2026-08-28 08:02:34'),
(62, 'VND048-94L0', 1, 'Miracle Promotion Display', '08992036020', 'Jl. Terusan Jakarta no.53 samping BORMA ANTAPANI, Cicaheum, Kec. Kiaracondong, Kota Bandung, Jawa Barat 40291', '2026-08-28 08:10:29', '2026-08-28 08:10:29'),
(63, 'VND049-9VPA', 1, 'MCO', '081219755596', 'Unnamed Road, Jl. Faliman Jaya No.22, RT.005/RW.007, Jurumudi Baru, Benda, Tangerang City, Banten 15124', '2026-08-28 08:11:04', '2026-08-28 08:11:04'),
(64, 'VND050-AJ3G', 1, 'Mulia Box', '08112121103', 'Jl. Radio Palasari Road No.102, Citeureup, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40257', '2026-08-28 08:11:34', '2026-08-28 08:11:34'),
(65, 'VND051-BJVM', 1, 'Brosur Kilat Surabaya', '083830642792', 'Jl. Ngagel Jaya Utara No.97, Baratajaya, Kec. Gubeng, Surabaya, Jawa Timur 60284', '2026-08-28 08:12:23', '2026-08-28 08:12:23'),
(66, 'VND052-CES2', 1, 'Blooming Deals', '081286515646', 'Jl. Tanjung Pura II No.10, RT.5/RW.4, Pegadungan, Kec. Kalideres, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11830', '2026-08-28 08:13:02', '2026-08-28 08:13:02'),
(67, 'VND053-DU80', 1, 'J2 Mediatama', '0818872250', 'Jl latumenten 33, Jakarta barat', '2026-08-28 08:14:08', '2026-08-28 08:14:08'),
(68, 'VND054-EQHK', 1, 'D\'Sell Topi', '089698642733', 'Jl. Inpres No. 43, RT.2/RW.2, Cigondewah Hilir, Margaasih, Bandung', '2026-08-28 08:14:50', '2026-08-28 08:14:50'),
(69, 'VND055-FSC1', 1, 'Zona Cetak Bandung', '08979295634', 'Jl. A. Yani No.757 B, dekat, Cicaheum, Kec. Antapani, Kota Bandung, Jawa Barat 40125', '2026-08-28 08:15:39', '2026-08-28 08:15:39');

-- --------------------------------------------------------

--
-- Table structure for table `v_closed_order_revenue`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`u966833962_erp_hervent`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_closed_order_revenue`  AS SELECT `o`.`id` AS `order_id`, `o`.`division_id` AS `division_id`, `o`.`created_by` AS `user_id`, `u`.`username` AS `username`, `o`.`project_name` AS `project_name`, `o`.`customer_name` AS `customer_name`, `o`.`order_date` AS `order_date`, `o`.`closed_at` AS `closed_at`, `o`.`subtotal` AS `subtotal`, `o`.`shipping_cost` AS `shipping_cost`, `o`.`total_amount` AS `total_amount` FROM (`orders` `o` left join `users` `u` on(`u`.`id` = `o`.`created_by`)) WHERE `o`.`lead_status` = 'Closed' ;
-- Error reading data for table u966833962_erp_hervent.v_closed_order_revenue: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `u966833962_erp_hervent`.`v_closed_order_revenue`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `v_products_full`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`u966833962_erp_hervent`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_products_full`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `name`, `p`.`division_id` AS `division_id`, `d`.`name` AS `division_name`, `d`.`code` AS `division_code`, `p`.`vendor_id` AS `vendor_id`, `v`.`name` AS `vendor_name`, `pc`.`id` AS `category_id`, `pc`.`name` AS `category_name`, `psc`.`id` AS `subcategory_id`, `psc`.`name` AS `subcategory_name`, `sc`.`id` AS `supplier_code_id`, `sc`.`code` AS `supplier_code`, `hc`.`id` AS `hv_code_id`, `hc`.`code` AS `hv_code`, `p`.`cost_price` AS `cost_price`, `p`.`profit_margin` AS `profit_margin`, `p`.`selling_price` AS `selling_price`, `p`.`quantity` AS `quantity`, `p`.`image_path` AS `image_path`, `p`.`is_active` AS `is_active`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at` FROM ((((((`products` `p` join `hv_codes` `hc` on(`hc`.`id` = `p`.`hv_code_id`)) join `supplier_codes` `sc` on(`sc`.`id` = `hc`.`supplier_code_id`)) join `product_subcategories` `psc` on(`psc`.`id` = `sc`.`subcategory_id`)) join `product_categories` `pc` on(`pc`.`id` = `psc`.`category_id`)) left join `vendors` `v` on(`v`.`id` = `p`.`vendor_id`)) left join `divisions` `d` on(`d`.`id` = `p`.`division_id`)) ;
-- Error reading data for table u966833962_erp_hervent.v_products_full: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `u966833962_erp_hervent`.`v_products_full`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `v_stock_purchases`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`u966833962_erp_hervent`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_stock_purchases`  AS SELECT `sm`.`id` AS `id`, `sm`.`movement_date` AS `tanggal`, `sm`.`product_id` AS `product_id`, `p`.`name` AS `product_name`, `sm`.`quantity_change` AS `quantity`, `sm`.`unit_cost` AS `harga_modal`, `sm`.`total_cost` AS `jumlah`, `sm`.`vendor_id` AS `vendor_id`, `v`.`name` AS `vendor_name`, `sm`.`description` AS `keterangan`, `sm`.`created_by` AS `created_by`, `u`.`username` AS `created_by_username`, `sm`.`created_at` AS `created_at` FROM (((`stock_movements` `sm` join `products` `p` on(`p`.`id` = `sm`.`product_id`)) left join `vendors` `v` on(`v`.`id` = `sm`.`vendor_id`)) left join `users` `u` on(`u`.`id` = `sm`.`created_by`)) WHERE `sm`.`movement_type` = 'PURCHASE' ;
-- Error reading data for table u966833962_erp_hervent.v_stock_purchases: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `u966833962_erp_hervent`.`v_stock_purchases`' at line 1

--
-- Indexes for dumped tables
--

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_leads_status` (`lead_status`),
  ADD KEY `idx_leads_created_by` (`created_by`),
  ADD KEY `idx_leads_division` (`division_id`);

--
-- Indexes for table `lead_custom_processes`
--
ALTER TABLE `lead_custom_processes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_custom_process` (`lead_id`,`process_name`);

--
-- Indexes for table `lead_items`
--
ALTER TABLE `lead_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lead_item_uid` (`lead_id`,`uid`),
  ADD KEY `fk_lead_items_product` (`product_id`);

--
-- Indexes for table `print_types`
--
ALTER TABLE `print_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`);

--
-- Indexes for table `production_timeline`
--
ALTER TABLE `production_timeline`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lead_stage` (`lead_id`,`stage`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_products_name` (`name`),
  ADD KEY `idx_products_division` (`division_id`),
  ADD KEY `fk_products_vendor` (`vendor_id`),
  ADD KEY `fk_products_subcategory` (`subcategory_id`),
  ADD KEY `fk_products_code` (`product_code_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `product_codes`
--
ALTER TABLE `product_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_product_code` (`subcategory_id`,`supplier_code`,`hv_code`);

--
-- Indexes for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_subcategory` (`category_id`,`name`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_stock_product` (`product_id`),
  ADD KEY `fk_stock_division` (`division_id`);

--
-- Indexes for table `stock_reservations`
--
ALTER TABLE `stock_reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_reservation` (`lead_id`,`product_id`),
  ADD KEY `fk_reservation_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_division` (`division_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `idx_vendor_division` (`division_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1366;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `lead_custom_processes`
--
ALTER TABLE `lead_custom_processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_items`
--
ALTER TABLE `lead_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `print_types`
--
ALTER TABLE `print_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `production_timeline`
--
ALTER TABLE `production_timeline`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=467;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `product_codes`
--
ALTER TABLE `product_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=526;

--
-- AUTO_INCREMENT for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_reservations`
--
ALTER TABLE `stock_reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=764;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `fk_leads_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lead_custom_processes`
--
ALTER TABLE `lead_custom_processes`
  ADD CONSTRAINT `fk_custom_process_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lead_items`
--
ALTER TABLE `lead_items`
  ADD CONSTRAINT `fk_lead_items_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lead_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `production_timeline`
--
ALTER TABLE `production_timeline`
  ADD CONSTRAINT `fk_timeline_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_code` FOREIGN KEY (`product_code_id`) REFERENCES `product_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `product_subcategories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_codes`
--
ALTER TABLE `product_codes`
  ADD CONSTRAINT `fk_codes_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `product_subcategories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `fk_stock_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_stock_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_reservations`
--
ALTER TABLE `stock_reservations`
  ADD CONSTRAINT `fk_reservation_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservation_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `fk_vendors_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
