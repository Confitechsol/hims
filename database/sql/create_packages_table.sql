-- Create packages table
CREATE TABLE IF NOT EXISTS `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` varchar(8) DEFAULT NULL,
  `branch_id` varchar(8) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `account_head` varchar(100) DEFAULT NULL,
  `gst_amount` decimal(10,2) DEFAULT 0.00,
  `package_rate` decimal(10,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `packages_hospital_id_branch_id_index` (`hospital_id`,`branch_id`),
  KEY `packages_status_index` (`status`),
  KEY `packages_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

