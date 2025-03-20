-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table nsbm.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.cache: ~0 rows (approximately)

-- Dumping structure for table nsbm.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.cache_locks: ~0 rows (approximately)

-- Dumping structure for table nsbm.census_entries
CREATE TABLE IF NOT EXISTS `census_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ward_id` bigint unsigned NOT NULL,
  `hours24_census` int NOT NULL,
  `cf_patient_2400` int NOT NULL,
  `bed_occupancy_rate` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `census_entries_ward_id_foreign` (`ward_id`),
  CONSTRAINT `census_entries_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.census_entries: ~0 rows (approximately)

-- Dumping structure for table nsbm.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table nsbm.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.jobs: ~0 rows (approximately)

-- Dumping structure for table nsbm.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.job_batches: ~0 rows (approximately)

-- Dumping structure for table nsbm.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.migrations: ~9 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000001_create_cache_table', 1),
	(2, '0001_01_01_000002_create_jobs_table', 1),
	(3, '2025_03_19_022733_create_users_table', 1),
	(4, '2025_03_19_022749_create_wards_table', 1),
	(5, '2025_03_19_022816_create_shifts_table', 1),
	(6, '2025_03_19_022829_create_ward_entries_table', 1),
	(7, '2025_03_19_022858_create_census_entries_table', 1),
	(8, '2025_03_19_024333_create_user_ward_table', 1),
	(9, '2025_03_19_031736_create_sessions_table', 1);

-- Dumping structure for table nsbm.shifts
CREATE TABLE IF NOT EXISTS `shifts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shifts_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.shifts: ~3 rows (approximately)
INSERT INTO `shifts` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'AM SHIFT', '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(2, 'PM SHIFT', '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(3, 'ND SHIFT', '2025-03-18 19:48:34', '2025-03-18 19:48:34');

-- Dumping structure for table nsbm.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'admin', '$2y$12$7qmr87RC/zCZ9hwzdo/zAerZYncFKxEphwLxzDOgNbR.B3uKZzgTy', '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(2, 'Ren', '$2y$12$4oJgPtPxXXyLNpOoU/.upO93CSIglw3jfFxdu0SfZAS5fw8fyuj5.', '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(3, 'Rize', '$2y$12$1ZkkYJzVqMgG7u2ZS8gKKeECqKUuq5LRB19wd5flx8o3jUe4SsXAS', '2025-03-18 19:48:34', '2025-03-18 19:48:34');

-- Dumping structure for table nsbm.user_ward
CREATE TABLE IF NOT EXISTS `user_ward` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ward_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_ward_user_id_ward_id_unique` (`user_id`,`ward_id`),
  KEY `user_ward_ward_id_foreign` (`ward_id`),
  CONSTRAINT `user_ward_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_ward_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.user_ward: ~10 rows (approximately)
INSERT INTO `user_ward` (`id`, `user_id`, `ward_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, NULL, NULL),
	(2, 1, 2, NULL, NULL),
	(3, 1, 3, NULL, NULL),
	(4, 1, 4, NULL, NULL),
	(5, 1, 5, NULL, NULL),
	(6, 1, 6, NULL, NULL),
	(7, 2, 1, NULL, NULL),
	(8, 2, 2, NULL, NULL),
	(9, 3, 3, NULL, NULL),
	(10, 3, 6, NULL, NULL);

-- Dumping structure for table nsbm.wards
CREATE TABLE IF NOT EXISTS `wards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_bed` int NOT NULL,
  `total_licensed_op_beds` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wards_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.wards: ~6 rows (approximately)
INSERT INTO `wards` (`id`, `name`, `total_bed`, `total_licensed_op_beds`, `created_at`, `updated_at`) VALUES
	(1, 'Medical Ward', 5, 2, '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(2, 'Surgical Ward', 21, 15, '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(3, 'New Wing', 8, 8, '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(4, 'ICU', 5, 2, '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(5, 'Nursery', 10, 10, '2025-03-18 19:48:34', '2025-03-18 19:48:34'),
	(6, 'Maternity Suite (L&D)', 6, 2, '2025-03-18 19:48:34', '2025-03-18 19:48:34');

-- Dumping structure for table nsbm.ward_entries
CREATE TABLE IF NOT EXISTS `ward_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ward_id` bigint unsigned NOT NULL,
  `shift_id` bigint unsigned NOT NULL,
  `cf_patient` int NOT NULL,
  `total_patient` int DEFAULT NULL,
  `shift_bor` decimal(5,2) NOT NULL,
  `total_admission` int NOT NULL,
  `total_transfer_in` int NOT NULL,
  `total_transfer_out` int NOT NULL,
  `total_discharge` int NOT NULL,
  `aor` int NOT NULL,
  `total_staff_on_duty` int NOT NULL,
  `overtime` int NOT NULL,
  `total_daily_patients` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ward_entries_user_id_foreign` (`user_id`),
  KEY `ward_entries_ward_id_foreign` (`ward_id`),
  KEY `ward_entries_shift_id_foreign` (`shift_id`),
  CONSTRAINT `ward_entries_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ward_entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ward_entries_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nsbm.ward_entries: ~3 rows (approximately)
INSERT INTO `ward_entries` (`id`, `user_id`, `ward_id`, `shift_id`, `cf_patient`, `total_patient`, `shift_bor`, `total_admission`, `total_transfer_in`, `total_transfer_out`, `total_discharge`, `aor`, `total_staff_on_duty`, `overtime`, `total_daily_patients`, `created_at`, `updated_at`) VALUES
	(4, 2, 2, 1, 4, 7, 46.67, 2, 2, 0, 1, 0, 3, 2, NULL, '2025-03-18 22:36:31', '2025-03-18 22:36:31'),
	(5, 2, 2, 2, 7, 12, 80.00, 5, 2, 0, 1, 1, 3, 1, NULL, '2025-03-18 23:08:00', '2025-03-18 23:08:00'),
	(6, 2, 2, 3, 12, 15, 100.00, 2, 2, 0, 1, 0, 1, 2, 15, '2025-03-18 23:13:26', '2025-03-18 23:13:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
