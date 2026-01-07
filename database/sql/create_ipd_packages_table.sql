-- Create ipd_packages table
CREATE TABLE IF NOT EXISTS `ipd_packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ipd_id` bigint(20) unsigned NOT NULL,
  `package_id` bigint(20) unsigned NOT NULL,
  `applied_date` date NOT NULL,
  `applied_by` bigint(20) unsigned DEFAULT NULL,
  `package_rate` decimal(10,2) DEFAULT 0.00,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `gst_amount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'applied' COMMENT 'applied, completed, cancelled',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ipd_packages_ipd_id_index` (`ipd_id`),
  KEY `ipd_packages_package_id_index` (`package_id`),
  KEY `ipd_packages_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

