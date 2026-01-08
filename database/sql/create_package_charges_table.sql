-- Create package_charges table
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

