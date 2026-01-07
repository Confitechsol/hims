-- =====================================================
-- Package Management System - Complete SQL Script
-- =====================================================
-- This script creates all tables required for the 
-- Package Management System in HIMS
-- =====================================================

-- 1. Create packages table
-- =====================================================
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

-- 2. Create package_charges table
-- =====================================================
CREATE TABLE IF NOT EXISTS `package_charges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `charge_type` varchar(100) NOT NULL COMMENT 'Bed Charges, O.T. Charges, Doctor Charges, etc.',
  `charge_category_id` bigint(20) unsigned DEFAULT NULL,
  `charge_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `is_percentage` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_charges_package_id_index` (`package_id`),
  KEY `package_charges_charge_type_index` (`charge_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create package_excludes table
-- =====================================================
CREATE TABLE IF NOT EXISTS `package_excludes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `charge_category_id` bigint(20) unsigned DEFAULT NULL,
  `charge_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_excludes_package_id_index` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create ipd_packages table
-- =====================================================
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

-- =====================================================
-- End of Package Management System SQL Script
-- =====================================================

