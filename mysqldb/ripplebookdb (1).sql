-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 08, 2025 at 04:32 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ripplebookdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_admin_logs`
--

DROP TABLE IF EXISTS `audit_admin_logs`;
CREATE TABLE IF NOT EXISTS `audit_admin_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_date_time` datetime NOT NULL,
  `entity_area` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_admin_logs`
--

INSERT INTO `audit_admin_logs` (`id`, `user_id`, `user_name`, `user_role`, `action_type`, `action_date_time`, `entity_area`, `old_values`, `new_values`, `description`, `ip_address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'John admin', 'ADMIN', 'Login', '2025-03-01 12:49:22', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-03-01 07:19:23', '2025-03-01 07:19:23', NULL),
(2, 1, 'John admin', 'ADMIN', 'Login', '2025-05-22 06:06:18', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-05-22 00:36:18', '2025-05-22 00:36:18', NULL),
(3, 2, 'Joshn Doe', 'Coordinator', 'Login', '2025-05-22 06:12:11', 'User Management', NULL, '{\"user_id\":2,\"email\":\"workgen353@gmail.com\"}', 'User with ID 2, named Joshn Doe, User Role (Coordinator), logged in successfully.', '127.0.0.1', '2025-05-22 00:42:11', '2025-05-22 00:42:11', NULL),
(4, 2, 'Joshn Doe', 'Coordinator', 'Login', '2025-05-22 06:15:46', 'User Management', NULL, '{\"user_id\":2,\"email\":\"workgen353@gmail.com\"}', 'User with ID 2, named Joshn Doe, User Role (Coordinator), logged in successfully.', '127.0.0.1', '2025-05-22 00:45:46', '2025-05-22 00:45:46', NULL),
(5, 1, 'John admin', 'ADMIN', 'Login', '2025-05-22 06:16:02', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-05-22 00:46:02', '2025-05-22 00:46:02', NULL),
(6, 1, 'John admin', 'ADMIN', 'Login', '2025-06-02 16:24:12', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-06-02 10:54:12', '2025-06-02 10:54:12', NULL),
(7, 1, 'John admin', 'ADMIN', 'Create', '2025-06-02 16:24:38', 'User Management', NULL, '{\"name\":\"Joshn Doe\",\"email\":\"workgen3523@gmail.com\",\"role\":\"Coordinator\",\"created_at\":\"2025-06-02T16:24:31.000000Z\"}', 'Created new user Joshn Doe (ID: 3)', '127.0.0.1', '2025-06-02 10:54:38', '2025-06-02 10:54:38', NULL),
(8, 1, 'John admin', 'ADMIN', 'Login', '2025-06-02 16:35:46', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-06-02 11:05:46', '2025-06-02 11:05:46', NULL),
(9, 1, 'John admin', 'ADMIN', 'Create', '2025-06-02 16:36:04', 'User Management', NULL, '{\"name\":\"Joshn Doe\",\"email\":\"workgen3523w@gmail.com\",\"role\":\"Coordinator\",\"created_at\":\"2025-06-02T16:35:57.000000Z\"}', 'Created new user Joshn Doe (ID: 4)', '127.0.0.1', '2025-06-02 11:06:04', '2025-06-02 11:06:04', NULL),
(10, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-02 16:39:45', 'User Management', '{\"name\":\"Joshn Doe\",\"email\":\"workgen3523w@gmail.com\",\"status\":null,\"deleted_at\":null}', '{\"status\":\"inactive\",\"deleted_at\":\"2025-06-02T16:39:45.000000Z\"}', 'Soft deleted user Joshn Doe (ID: 4)', '127.0.0.1', '2025-06-02 11:09:45', '2025-06-02 11:09:45', NULL),
(11, 1, 'John admin', 'ADMIN', 'Update', '2025-06-02 17:13:01', 'Damma Sermons Request', '{\"id\":1,\"first_name\":\"Charitha\",\"last_name\":\"Suranga\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"chariths615@gmail.com\",\"date\":\"2025-02-27\",\"time\":\"04:40\",\"count\":null,\"option\":null,\"birthday\":0,\"sevenday\":1,\"warming\":1,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"175.157.153.162\",\"created_at\":\"2025-02-16 11:05:25\",\"updated_at\":\"2025-02-16 11:05:25\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"date\":\"2023-12-25\",\"time\":\"10:00 AM\",\"count\":\"5\",\"option\":\"Some option\",\"birthday\":1,\"sevenday\":0,\"warming\":1,\"threemonths\":0,\"oneyear\":1,\"annually\":0,\"weddings\":1,\"ip_address\":\"175.157.153.162\",\"created_at\":\"2025-02-16 11:05:25\",\"updated_at\":\"2025-06-02 17:13:01\",\"deleted_at\":null}', 'Updated sermon request ID: 1', '127.0.0.1', '2025-06-02 11:43:01', '2025-06-02 11:43:01', NULL),
(12, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-02 17:14:01', 'Damma Sermons Request', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"date\":\"2023-12-25\",\"time\":\"10:00 AM\",\"count\":\"5\",\"option\":\"Some option\",\"birthday\":1,\"sevenday\":0,\"warming\":1,\"threemonths\":0,\"oneyear\":1,\"annually\":0,\"weddings\":1,\"ip_address\":\"175.157.153.162\",\"created_at\":\"2025-02-16 11:05:25\",\"updated_at\":\"2025-06-02 17:13:01\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-02T17:14:01.308468Z\"}', 'Soft deleted sermon request ID: 1', '127.0.0.1', '2025-06-02 11:44:01', '2025-06-02 11:44:01', NULL),
(13, 1, 'John admin', 'ADMIN', 'Login', '2025-06-02 18:51:18', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-06-02 13:21:18', '2025-06-02 13:21:18', NULL),
(14, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-02 18:53:32', 'Dana At Home Request', '{\"id\":1,\"first_name\":\"charitha\",\"last_name\":\"Suranga\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"chariths615@gmail.com\",\"specific_event\":null,\"other\":null,\"dana_for_morning\":1,\"dana_for_lunch\":0,\"birthday\":1,\"sevenday\":0,\"warming\":0,\"threemonths\":1,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 12:51:21\",\"updated_at\":\"2025-03-01 12:51:21\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-02T18:53:32.883564Z\"}', 'Soft deleted Dana At Home request ID: 1', '127.0.0.1', '2025-06-02 13:23:32', '2025-06-02 13:23:32', NULL),
(15, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-02 19:00:55', 'Dana Payment Request', '{\"id\":1,\"first_name\":\"das\",\"last_name\":\"da\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"john.doe@example.com\",\"dana_for_morning\":1,\"dana_for_lunch\":0,\"dana_event_date\":\"2010\\/04\\/20\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 13:41:04\",\"updated_at\":\"2025-03-01 13:41:04\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-02T19:00:55.557601Z\"}', 'Soft deleted Dana Payment request ID: 1', '127.0.0.1', '2025-06-02 13:30:55', '2025-06-02 13:30:55', NULL),
(16, 1, 'John admin', 'ADMIN', 'Update', '2025-06-03 03:44:06', 'Dana Request', '{\"id\":1,\"first_name\":\"test\",\"last_name\":\"las\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"string@gmail.com\",\"dana_event_date\":\"2010\\/04\\/20\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:19:51\",\"updated_at\":\"2025-03-01 17:19:51\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"dana_event_date\":\"2023-12-25\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:19:51\",\"updated_at\":\"2025-06-03 03:44:06\",\"deleted_at\":null}', 'Updated Dana request ID: 1', '127.0.0.1', '2025-06-02 22:14:06', '2025-06-02 22:14:06', NULL),
(17, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-03 03:44:19', 'Dana Request', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"dana_event_date\":\"2023-12-25\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:19:51\",\"updated_at\":\"2025-06-03 03:44:06\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-03T03:44:19.506854Z\"}', 'Soft deleted Dana request ID: 1', '127.0.0.1', '2025-06-02 22:14:19', '2025-06-02 22:14:19', NULL),
(18, 1, 'John admin', 'ADMIN', 'Update', '2025-06-03 03:55:15', 'External Retreat Request Form Glen Waverley', '{\"id\":1,\"first_name\":\"sadasd\",\"last_name\":\"dasdas\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"string@gmail.com\",\"number_of_people\":10,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:31:34\",\"updated_at\":\"2025-03-01 17:31:34\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:31:34\",\"updated_at\":\"2025-06-03 03:55:15\",\"deleted_at\":null}', 'Updated external retreat request ID: 1', '127.0.0.1', '2025-06-02 22:25:15', '2025-06-02 22:25:15', NULL),
(19, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-03 03:55:29', 'External Retreat Request Form Glen Waverley', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:31:34\",\"updated_at\":\"2025-06-03 03:55:15\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-03T03:55:29.717134Z\"}', 'Soft deleted external retreat request ID: 1', '127.0.0.1', '2025-06-02 22:25:29', '2025-06-02 22:25:29', NULL),
(20, 1, 'John admin', 'ADMIN', 'Update', '2025-06-03 04:01:27', 'External Retreat Request Form Hallam', '{\"id\":1,\"first_name\":\"dasdas\",\"last_name\":\"da\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"string@gmail.com\",\"date\":\"2010\\/04\\/20\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:44:49\",\"updated_at\":\"2025-03-01 17:44:49\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:44:49\",\"updated_at\":\"2025-06-03 04:01:27\",\"deleted_at\":null}', 'Updated external retreat request ID: 1', '127.0.0.1', '2025-06-02 22:31:27', '2025-06-02 22:31:27', NULL),
(21, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-03 04:01:40', 'External Retreat Request Form Hallam', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 17:44:49\",\"updated_at\":\"2025-06-03 04:01:27\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-03T04:01:40.116949Z\"}', 'Soft deleted external retreat request ID: 1', '127.0.0.1', '2025-06-02 22:31:40', '2025-06-02 22:31:40', NULL),
(22, 1, 'John admin', 'ADMIN', 'Login', '2025-06-03 04:02:28', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-06-02 22:32:28', '2025-06-02 22:32:28', NULL),
(23, 1, 'John admin', 'ADMIN', 'Update', '2025-06-03 04:09:25', 'External Retreat Request Form Packenham', '{\"id\":1,\"first_name\":\"das\",\"last_name\":\"das\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"adweb@gmail.com\",\"number_of_people\":10,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 18:40:33\",\"updated_at\":\"2025-03-01 18:40:33\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 18:40:33\",\"updated_at\":\"2025-06-03 04:09:25\",\"deleted_at\":null}', 'Updated external retreat request ID: 1', '127.0.0.1', '2025-06-02 22:39:25', '2025-06-02 22:39:25', NULL),
(24, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-03 04:09:35', 'External Retreat Request Form Packenham', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 18:40:33\",\"updated_at\":\"2025-06-03 04:09:25\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-03T04:09:35.364102Z\"}', 'Soft deleted external retreat request ID: 1', '127.0.0.1', '2025-06-02 22:39:35', '2025-06-02 22:39:35', NULL),
(25, 1, 'John admin', 'ADMIN', 'Update', '2025-06-03 04:13:28', 'Future Plans Request Form', '{\"id\":1,\"first_name\":\"dasdas\",\"last_name\":\"dasdas\",\"address\":\"dasda\",\"city\":\"colomo\",\"postal_code\":\"10300\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"john.doe@example.com\",\"contribute\":\"noo\",\"inquire\":\"yres\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 19:32:56\",\"updated_at\":\"2025-03-01 19:32:56\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"address\":\"123 Main St Updated\",\"city\":\"Melbourne\",\"postal_code\":\"3000\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"contribute\":\"I want to contribute with my time\",\"inquire\":\"Looking for volunteer opportunities\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 19:32:56\",\"updated_at\":\"2025-06-03 04:13:28\",\"deleted_at\":null}', 'Updated future plans request ID: 1', '127.0.0.1', '2025-06-02 22:43:28', '2025-06-02 22:43:28', NULL),
(26, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-03 04:13:36', 'Future Plans Request Form', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"address\":\"123 Main St Updated\",\"city\":\"Melbourne\",\"postal_code\":\"3000\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"contribute\":\"I want to contribute with my time\",\"inquire\":\"Looking for volunteer opportunities\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 19:32:56\",\"updated_at\":\"2025-06-03 04:13:28\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-03T04:13:36.692026Z\"}', 'Soft deleted future plans request ID: 1', '127.0.0.1', '2025-06-02 22:43:36', '2025-06-02 22:43:36', NULL),
(27, 1, 'John admin', 'ADMIN', 'Update', '2025-06-03 04:41:39', 'Five Year Request Form', '{\"id\":1,\"first_name\":\"dasdas\",\"last_name\":\"dsadas\",\"date_of_birth\":\"2025-03-02\",\"gender\":\"Female\",\"street_address_line_1\":\"dasdasd\",\"street_address_line_2\":null,\"city\":null,\"postal_code\":null,\"country\":null,\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"string@gmail.com\",\"5_land_plots\":0,\"10_land_plots\":0,\"20_land_plots\":0,\"50_land_plots\":0,\"query\":null,\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 04:59:39\",\"updated_at\":\"2025-03-02 04:59:39\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"date_of_birth\":\"1990-01-01\",\"gender\":\"Male\",\"street_address_line_1\":\"123 Main St Updated\",\"street_address_line_2\":\"Apt 4B\",\"city\":\"Melbourne\",\"postal_code\":\"3000\",\"country\":\"Australia\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"5_land_plots\":0,\"10_land_plots\":1,\"20_land_plots\":0,\"50_land_plots\":0,\"query\":\"Updated query about the 10 land plots option\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 04:59:39\",\"updated_at\":\"2025-06-03 04:41:39\",\"deleted_at\":null}', 'Updated five year request ID: 1', '127.0.0.1', '2025-06-02 23:11:39', '2025-06-02 23:11:39', NULL),
(28, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-03 04:42:01', 'Five Year Request Form', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"date_of_birth\":\"1990-01-01\",\"gender\":\"Male\",\"street_address_line_1\":\"123 Main St Updated\",\"street_address_line_2\":\"Apt 4B\",\"city\":\"Melbourne\",\"postal_code\":\"3000\",\"country\":\"Australia\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"5_land_plots\":0,\"10_land_plots\":1,\"20_land_plots\":0,\"50_land_plots\":0,\"query\":\"Updated query about the 10 land plots option\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 04:59:39\",\"updated_at\":\"2025-06-03 04:41:39\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-03T04:42:01.936565Z\"}', 'Soft deleted five year request ID: 1', '127.0.0.1', '2025-06-02 23:12:01', '2025-06-02 23:12:01', NULL),
(29, 1, 'John admin', 'ADMIN', 'Update', '2025-06-03 04:50:56', 'Gilan Pasa Request', '{\"id\":1,\"first_name\":\"Charitha\",\"last_name\":\"Suranga\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"adweb@gmail.com\",\"date\":\"2010\\/04\\/20\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 05:45:51\",\"updated_at\":\"2025-03-02 05:45:51\",\"deleted_at\":null}', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 05:45:51\",\"updated_at\":\"2025-06-03 04:50:56\",\"deleted_at\":null}', 'Updated Gilan Pasa request ID: 1', '127.0.0.1', '2025-06-02 23:20:56', '2025-06-02 23:20:56', NULL),
(30, 1, 'John admin', 'ADMIN', 'Delete', '2025-06-03 04:51:25', 'Gilan Pasa Request', '{\"id\":1,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 05:45:51\",\"updated_at\":\"2025-06-03 04:50:56\",\"deleted_at\":null}', '{\"deleted_at\":\"2025-06-03T04:51:25.328262Z\"}', 'Soft deleted Gilan Pasa request ID: 1', '127.0.0.1', '2025-06-02 23:21:25', '2025-06-02 23:21:25', NULL),
(31, 1, 'John admin', 'ADMIN', 'Login', '2025-06-07 02:56:22', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-06-06 21:26:22', '2025-06-06 21:26:22', NULL),
(32, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:06:54', 'Damma Sermons Request', '{\"id\":4,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"date\":\"2025-03-10\",\"time\":\"10.20\",\"count\":null,\"option\":null,\"birthday\":1,\"sevenday\":1,\"warming\":0,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"203.0.113.4\",\"created_at\":\"2025-06-07 03:06:23\",\"updated_at\":\"2025-06-07 03:06:23\",\"deleted_at\":null}', '{\"id\":4,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"date\":\"2023-12-25\",\"time\":\"10:00 AM\",\"count\":\"5\",\"option\":\"Some option\",\"birthday\":1,\"sevenday\":0,\"warming\":1,\"threemonths\":0,\"oneyear\":1,\"annually\":0,\"weddings\":1,\"ip_address\":\"203.0.113.4\",\"created_at\":\"2025-06-07 03:06:23\",\"updated_at\":\"2025-06-07 03:06:54\",\"deleted_at\":null}', 'Updated sermon request ID: 4', '127.0.0.1', '2025-06-06 21:36:54', '2025-06-06 21:36:54', NULL),
(33, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:11:14', 'Dana At Home Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"specific_event\":\"Annual Ceremony\",\"other\":\"Special dietary requirements\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"birthday\":1,\"sevenday\":0,\"warming\":1,\"threemonths\":0,\"oneyear\":1,\"annually\":0,\"weddings\":1,\"ip_address\":\"203.0.113.4\",\"created_at\":\"2025-05-22 18:00:42\",\"updated_at\":\"2025-05-22 18:00:42\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"specific_event\":\"Some special occasion\",\"other\":\"Additional notes\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"birthday\":1,\"sevenday\":0,\"warming\":1,\"threemonths\":0,\"oneyear\":1,\"annually\":0,\"weddings\":1,\"ip_address\":\"203.0.113.4\",\"created_at\":\"2025-05-22 18:00:42\",\"updated_at\":\"2025-06-07 03:11:14\",\"deleted_at\":null}', 'Updated Dana At Home request ID: 2', '127.0.0.1', '2025-06-06 21:41:14', '2025-06-06 21:41:14', NULL),
(34, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:13:10', 'Dana Payment Request', '{\"id\":2,\"first_name\":\"Alice\",\"last_name\":\"Smith\",\"mobile_number\":\"9876543211\",\"wt_number\":\"1122334455\",\"email\":\"alice.smith@example.com\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"dana_event_date\":\"2025-05-25\",\"ip_address\":\"203.0.113.45\",\"created_at\":\"2025-05-22 18:03:34\",\"updated_at\":\"2025-05-22 18:03:34\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"dana_event_date\":\"2024-12-31\",\"ip_address\":\"203.0.113.45\",\"created_at\":\"2025-05-22 18:03:34\",\"updated_at\":\"2025-06-07 03:13:10\",\"deleted_at\":null}', 'Updated Dana Payment request ID: 2', '127.0.0.1', '2025-06-06 21:43:10', '2025-06-06 21:43:10', NULL),
(35, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:14:34', 'Dana Request', '{\"id\":2,\"first_name\":\"Bob\",\"last_name\":\"Johnson\",\"mobile_number\":\"8765432109\",\"wt_number\":\"0789621706\",\"email\":\"bob.johnson@example.com\",\"dana_event_date\":\"2025-06-15\",\"ip_address\":\"198.51.100.22\",\"created_at\":\"2025-05-22 18:04:35\",\"updated_at\":\"2025-05-22 18:04:35\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"dana_event_date\":\"2023-12-25\",\"ip_address\":\"198.51.100.22\",\"created_at\":\"2025-05-22 18:04:35\",\"updated_at\":\"2025-06-07 03:14:34\",\"deleted_at\":null}', 'Updated Dana request ID: 2', '127.0.0.1', '2025-06-06 21:44:34', '2025-06-06 21:44:34', NULL),
(36, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:16:01', 'External Retreat Request Form Glen Waverley', '{\"id\":2,\"first_name\":\"Emma\",\"last_name\":\"Watson\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"emma.watson@example.com\",\"number_of_people\":5,\"ip_address\":\"203.0.113.10\",\"created_at\":\"2025-05-22 18:12:50\",\"updated_at\":\"2025-05-22 18:12:50\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.10\",\"created_at\":\"2025-05-22 18:12:50\",\"updated_at\":\"2025-06-07 03:16:01\",\"deleted_at\":null}', 'Updated external retreat request ID: 2', '127.0.0.1', '2025-06-06 21:46:01', '2025-06-06 21:46:01', NULL),
(37, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:17:47', 'External Retreat Request Form Hallam', '{\"id\":2,\"first_name\":\"Michael\",\"last_name\":\"Brown\",\"mobile_number\":\"8765432109\",\"wt_number\":\"0789614444\",\"email\":\"michael.b@example.com\",\"date\":\"2025-07-20\",\"ip_address\":\"198.51.100.33\",\"created_at\":\"2025-05-22 18:14:25\",\"updated_at\":\"2025-05-22 18:14:25\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"198.51.100.33\",\"created_at\":\"2025-05-22 18:14:25\",\"updated_at\":\"2025-06-07 03:17:47\",\"deleted_at\":null}', 'Updated external retreat request ID: 2', '127.0.0.1', '2025-06-06 21:47:47', '2025-06-06 21:47:47', NULL),
(38, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:19:04', 'External Retreat Request Form Packenham', '{\"id\":2,\"first_name\":\"David\",\"last_name\":\"Wilson\",\"mobile_number\":\"7654321098\",\"wt_number\":\"3344556677\",\"email\":\"d.wilson@example.com\",\"number_of_people\":2,\"ip_address\":\"203.0.113.55\",\"created_at\":\"2025-05-22 18:16:04\",\"updated_at\":\"2025-05-22 18:16:04\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.55\",\"created_at\":\"2025-05-22 18:16:04\",\"updated_at\":\"2025-06-07 03:19:04\",\"deleted_at\":null}', 'Updated external retreat request ID: 2', '127.0.0.1', '2025-06-06 21:49:04', '2025-06-06 21:49:04', NULL),
(39, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:20:30', 'Future Plans Request Form', '{\"id\":2,\"first_name\":\"dasdas\",\"last_name\":\"dasdas\",\"address\":\"dasda\",\"city\":\"colomo\",\"postal_code\":\"10300\",\"mobile_number\":\"0789621706\",\"wt_number\":\"0789621706\",\"email\":\"john.doe@example.com\",\"contribute\":\"noo\",\"inquire\":\"yres\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 19:33:13\",\"updated_at\":\"2025-03-01 19:33:13\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"address\":\"123 Main St\",\"city\":\"New York\",\"postal_code\":\"10001\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"contribute\":\"I want to contribute to future projects\",\"inquire\":\"I have questions about upcoming plans\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-01 19:33:13\",\"updated_at\":\"2025-06-07 03:20:30\",\"deleted_at\":null}', 'Updated future plans request ID: 2', '127.0.0.1', '2025-06-06 21:50:30', '2025-06-06 21:50:30', NULL),
(40, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:24:47', 'Five Year Request Form', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"date_of_birth\":\"1990-01-01\",\"gender\":\"Male\",\"street_address_line_1\":\"123 Main St\",\"street_address_line_2\":\"Apt 4B\",\"city\":\"New York\",\"postal_code\":\"10001\",\"country\":\"USA\",\"mobile_number\":\"+1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"5_land_plots\":1,\"10_land_plots\":0,\"20_land_plots\":1,\"50_land_plots\":0,\"query\":\"I have questions about payment options\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 05:06:24\",\"updated_at\":\"2025-06-07 03:24:04\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"date_of_birth\":\"1990-01-01\",\"gender\":\"Male\",\"street_address_line_1\":\"123 Main St Updated\",\"street_address_line_2\":\"Apt 4B\",\"city\":\"Melbourne\",\"postal_code\":\"3000\",\"country\":\"Australia\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"5_land_plots\":0,\"10_land_plots\":1,\"20_land_plots\":0,\"50_land_plots\":0,\"query\":\"Updated query about the 10 land plots option\",\"ip_address\":\"175.157.37.164\",\"created_at\":\"2025-03-02 05:06:24\",\"updated_at\":\"2025-06-07 03:24:46\",\"deleted_at\":null}', 'Updated five year request ID: 2', '127.0.0.1', '2025-06-06 21:54:47', '2025-06-06 21:54:47', NULL),
(41, 1, 'John admin', 'ADMIN', 'Update', '2025-06-07 03:27:21', 'Gilan Pasa Request', '{\"id\":2,\"first_name\":\"Lisa\",\"last_name\":\"Taylor\",\"mobile_number\":\"6543210987\",\"wt_number\":\"7788990011\",\"email\":\"l.taylor@example.com\",\"date\":\"2025-08-25\",\"ip_address\":\"198.51.100.44\",\"created_at\":\"2025-05-22 18:19:20\",\"updated_at\":\"2025-05-22 18:19:20\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"198.51.100.44\",\"created_at\":\"2025-05-22 18:19:20\",\"updated_at\":\"2025-06-07 03:27:21\",\"deleted_at\":null}', 'Updated Gilan Pasa request ID: 2', '127.0.0.1', '2025-06-06 21:57:21', '2025-06-06 21:57:21', NULL),
(42, 1, 'John admin', 'ADMIN', 'Login', '2025-06-08 03:25:14', 'User Management', NULL, '{\"user_id\":1,\"email\":\"admin@admin.com\"}', 'User with ID 1, named John admin, User Role (ADMIN), logged in successfully.', '127.0.0.1', '2025-06-07 21:55:14', '2025-06-07 21:55:14', NULL),
(43, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 03:37:02', 'Damma Sermons Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"date\":\"2025-03-10\",\"time\":\"10.20\",\"count\":null,\"option\":null,\"birthday\":1,\"sevenday\":1,\"warming\":0,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"203.0.113.4\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 08:14:02\",\"updated_at\":\"2025-05-22 08:14:02\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"date\":\"2025-03-10\",\"time\":\"10.20\",\"count\":null,\"option\":null,\"birthday\":1,\"sevenday\":1,\"warming\":0,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"203.0.113.4\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 08:14:02\",\"updated_at\":\"2025-06-08 03:37:02\",\"deleted_at\":null}', 'Changed status of sermon request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:07:02', '2025-06-07 22:07:02', NULL),
(44, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 03:42:59', 'Damma Sermons Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"date\":\"2025-03-10\",\"time\":\"10.20\",\"count\":null,\"option\":null,\"birthday\":1,\"sevenday\":1,\"warming\":0,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"203.0.113.4\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 08:14:02\",\"updated_at\":\"2025-06-08 03:37:02\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"date\":\"2025-03-10\",\"time\":\"10.20\",\"count\":null,\"option\":null,\"birthday\":1,\"sevenday\":1,\"warming\":0,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"203.0.113.4\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 08:14:02\",\"updated_at\":\"2025-06-08 03:37:02\",\"deleted_at\":null}', 'Changed status of sermon request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:12:59', '2025-06-07 22:12:59', NULL),
(45, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 03:44:14', 'Damma Sermons Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"date\":\"2025-03-10\",\"time\":\"10.20\",\"count\":null,\"option\":null,\"birthday\":1,\"sevenday\":1,\"warming\":0,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"203.0.113.4\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 08:14:02\",\"updated_at\":\"2025-06-08 03:37:02\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"9876543210\",\"wt_number\":\"1234567890\",\"email\":\"john.doe@example.com\",\"date\":\"2025-03-10\",\"time\":\"10.20\",\"count\":null,\"option\":null,\"birthday\":1,\"sevenday\":1,\"warming\":0,\"threemonths\":0,\"oneyear\":0,\"annually\":0,\"weddings\":0,\"ip_address\":\"203.0.113.4\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 08:14:02\",\"updated_at\":\"2025-06-08 03:37:02\",\"deleted_at\":null}', 'Changed status of sermon request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:14:14', '2025-06-07 22:14:14', NULL),
(46, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 03:44:48', 'Dana At Home Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"specific_event\":\"Some special occasion\",\"other\":\"Additional notes\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"birthday\":1,\"sevenday\":0,\"warming\":1,\"threemonths\":0,\"oneyear\":1,\"annually\":0,\"weddings\":1,\"ip_address\":\"203.0.113.4\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 18:00:42\",\"updated_at\":\"2025-06-07 03:11:14\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"specific_event\":\"Some special occasion\",\"other\":\"Additional notes\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"birthday\":1,\"sevenday\":0,\"warming\":1,\"threemonths\":0,\"oneyear\":1,\"annually\":0,\"weddings\":1,\"ip_address\":\"203.0.113.4\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:00:42\",\"updated_at\":\"2025-06-08 03:44:48\",\"deleted_at\":null}', 'Changed status of Dana At Home request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:14:48', '2025-06-07 22:14:48', NULL),
(47, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 03:51:17', 'Dana Payment Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"dana_event_date\":\"2024-12-31\",\"ip_address\":\"203.0.113.45\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 18:03:34\",\"updated_at\":\"2025-06-07 03:13:10\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"dana_for_morning\":0,\"dana_for_lunch\":1,\"dana_event_date\":\"2024-12-31\",\"ip_address\":\"203.0.113.45\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:03:34\",\"updated_at\":\"2025-06-08 03:51:17\",\"deleted_at\":null}', 'Changed status of Dana Payment Request request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:21:17', '2025-06-07 22:21:17', NULL),
(48, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 03:54:02', 'Dana Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"dana_event_date\":\"2023-12-25\",\"ip_address\":\"198.51.100.22\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 18:04:35\",\"updated_at\":\"2025-06-07 03:14:34\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"dana_event_date\":\"2023-12-25\",\"ip_address\":\"198.51.100.22\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:04:35\",\"updated_at\":\"2025-06-08 03:54:02\",\"deleted_at\":null}', 'Changed status of Dana Request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:24:02', '2025-06-07 22:24:02', NULL),
(49, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 04:00:35', 'External Retreat Request Form Glen Waverley', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.10\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 18:12:50\",\"updated_at\":\"2025-06-07 03:16:01\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.10\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:12:50\",\"updated_at\":\"2025-06-08 04:00:35\",\"deleted_at\":null}', 'Changed status of Dana At Home request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:30:35', '2025-06-07 22:30:35', NULL),
(50, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 04:00:50', 'External Retreat Request Form Glen Waverley', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.10\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:12:50\",\"updated_at\":\"2025-06-08 04:00:35\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.10\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:12:50\",\"updated_at\":\"2025-06-08 04:00:35\",\"deleted_at\":null}', 'Changed status of Dana At Home request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:30:50', '2025-06-07 22:30:50', NULL),
(51, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 04:03:01', 'External Retreat Request Form Hallam', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"198.51.100.33\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 18:14:25\",\"updated_at\":\"2025-06-07 03:17:47\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"198.51.100.33\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:14:25\",\"updated_at\":\"2025-06-08 04:03:01\",\"deleted_at\":null}', 'Changed status of Dana At Home request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:33:01', '2025-06-07 22:33:01', NULL),
(52, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 04:06:23', 'External Retreat Request Form Packenham', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.55\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 18:16:04\",\"updated_at\":\"2025-06-07 03:19:04\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"number_of_people\":10,\"ip_address\":\"203.0.113.55\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:16:04\",\"updated_at\":\"2025-06-08 04:06:23\",\"deleted_at\":null}', 'Changed status of Dana At Home request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:36:23', '2025-06-07 22:36:23', NULL),
(53, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 04:09:12', 'Future Plans Request Form', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"address\":\"123 Main St\",\"city\":\"New York\",\"postal_code\":\"10001\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"contribute\":\"I want to contribute to future projects\",\"inquire\":\"I have questions about upcoming plans\",\"ip_address\":\"175.157.37.164\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-03-01 19:33:13\",\"updated_at\":\"2025-06-07 03:20:30\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe\",\"address\":\"123 Main St\",\"city\":\"New York\",\"postal_code\":\"10001\",\"mobile_number\":\"1234567890\",\"wt_number\":\"WT12345\",\"email\":\"john.doe@example.com\",\"contribute\":\"I want to contribute to future projects\",\"inquire\":\"I have questions about upcoming plans\",\"ip_address\":\"175.157.37.164\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-03-01 19:33:13\",\"updated_at\":\"2025-06-08 04:09:12\",\"deleted_at\":null}', 'Changed status of Future Plans Request Form request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:39:12', '2025-06-07 22:39:12', NULL),
(54, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 04:11:31', 'Five Year Request Form', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"date_of_birth\":\"1990-01-01\",\"gender\":\"Male\",\"street_address_line_1\":\"123 Main St Updated\",\"street_address_line_2\":\"Apt 4B\",\"city\":\"Melbourne\",\"postal_code\":\"3000\",\"country\":\"Australia\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"5_land_plots\":0,\"10_land_plots\":1,\"20_land_plots\":0,\"50_land_plots\":0,\"query\":\"Updated query about the 10 land plots option\",\"ip_address\":\"175.157.37.164\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-03-02 05:06:24\",\"updated_at\":\"2025-06-07 03:24:46\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"date_of_birth\":\"1990-01-01\",\"gender\":\"Male\",\"street_address_line_1\":\"123 Main St Updated\",\"street_address_line_2\":\"Apt 4B\",\"city\":\"Melbourne\",\"postal_code\":\"3000\",\"country\":\"Australia\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"5_land_plots\":0,\"10_land_plots\":1,\"20_land_plots\":0,\"50_land_plots\":0,\"query\":\"Updated query about the 10 land plots option\",\"ip_address\":\"175.157.37.164\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-03-02 05:06:24\",\"updated_at\":\"2025-06-08 04:11:31\",\"deleted_at\":null}', 'Changed status of Five Year Request Form request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:41:31', '2025-06-07 22:41:31', NULL),
(55, 1, 'John admin', 'ADMIN', 'Status Change', '2025-06-08 04:14:22', 'Gilan Pasa Request', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"198.51.100.44\",\"status\":\"Pending\",\"status_reason\":null,\"created_at\":\"2025-05-22 18:19:20\",\"updated_at\":\"2025-06-07 03:27:21\",\"deleted_at\":null}', '{\"id\":2,\"first_name\":\"John\",\"last_name\":\"Doe Updated\",\"mobile_number\":\"0771234567\",\"wt_number\":\"WT123\",\"email\":\"john.doe.updated@example.com\",\"date\":\"2023-12-26\",\"ip_address\":\"198.51.100.44\",\"status\":\"Approved\",\"status_reason\":\"Approved the request\",\"created_at\":\"2025-05-22 18:19:20\",\"updated_at\":\"2025-06-08 04:14:22\",\"deleted_at\":null}', 'Changed status of Gilan Pasa request ID: 2 to \'Approved\'', '127.0.0.1', '2025-06-07 22:44:22', '2025-06-07 22:44:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dana_at_homes`
--

DROP TABLE IF EXISTS `dana_at_homes`;
CREATE TABLE IF NOT EXISTS `dana_at_homes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specific_event` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dana_for_morning` tinyint(1) NOT NULL DEFAULT '0',
  `dana_for_lunch` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` tinyint(1) NOT NULL DEFAULT '0',
  `sevenday` tinyint(1) NOT NULL DEFAULT '0',
  `warming` tinyint(1) NOT NULL DEFAULT '0',
  `threemonths` tinyint(1) NOT NULL DEFAULT '0',
  `oneyear` tinyint(1) NOT NULL DEFAULT '0',
  `annually` tinyint(1) NOT NULL DEFAULT '0',
  `weddings` tinyint(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dana_at_homes`
--

INSERT INTO `dana_at_homes` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `specific_event`, `other`, `dana_for_morning`, `dana_for_lunch`, `birthday`, `sevenday`, `warming`, `threemonths`, `oneyear`, `annually`, `weddings`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'charitha', 'Suranga', '0789621706', '0789621706', 'chariths615@gmail.com', NULL, NULL, 1, 0, 1, 0, 0, 1, 0, 0, 0, '175.157.37.164', 'Pending', NULL, '2025-03-01 07:21:21', '2025-06-02 13:23:32', '2025-06-02 13:23:32'),
(2, 'John', 'Doe', '1234567890', 'WT12345', 'john.doe@example.com', 'Some special occasion', 'Additional notes', 0, 1, 1, 0, 1, 0, 1, 0, 1, '203.0.113.4', 'Approved', 'Approved the request', '2025-05-22 12:30:42', '2025-06-07 22:14:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dana_payment_requests`
--

DROP TABLE IF EXISTS `dana_payment_requests`;
CREATE TABLE IF NOT EXISTS `dana_payment_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dana_for_morning` tinyint(1) NOT NULL DEFAULT '0',
  `dana_for_lunch` tinyint(1) NOT NULL DEFAULT '0',
  `dana_event_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dana_payment_requests`
--

INSERT INTO `dana_payment_requests` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `dana_for_morning`, `dana_for_lunch`, `dana_event_date`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'das', 'da', '0789621706', '0789621706', 'john.doe@example.com', 1, 0, '2010/04/20', '175.157.37.164', 'Pending', NULL, '2025-03-01 08:11:04', '2025-06-02 13:30:55', '2025-06-02 13:30:55'),
(2, 'John', 'Doe', '1234567890', 'WT12345', 'john.doe@example.com', 0, 1, '2024-12-31', '203.0.113.45', 'Approved', 'Approved the request', '2025-05-22 12:33:34', '2025-06-07 22:21:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dana_requests`
--

DROP TABLE IF EXISTS `dana_requests`;
CREATE TABLE IF NOT EXISTS `dana_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dana_event_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dana_requests`
--

INSERT INTO `dana_requests` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `dana_event_date`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', '2023-12-25', '175.157.37.164', 'Pending', NULL, '2025-03-01 11:49:51', '2025-06-02 22:14:19', '2025-06-02 22:14:19'),
(2, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', '2023-12-25', '198.51.100.22', 'Approved', 'Approved the request', '2025-05-22 12:34:35', '2025-06-07 22:24:02', NULL),
(3, 'Bob', 'Johnson', '8765432109', '0789617444', 'bob.johnson@example.com', '2025-06-15', '198.51.100.22', 'Pending', NULL, '2025-05-22 12:38:43', '2025-05-22 12:38:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `external_retreat_hallams`
--

DROP TABLE IF EXISTS `external_retreat_hallams`;
CREATE TABLE IF NOT EXISTS `external_retreat_hallams` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_retreat_hallams`
--

INSERT INTO `external_retreat_hallams` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `date`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', '2023-12-26', '175.157.37.164', 'Pending', NULL, '2025-03-01 12:14:49', '2025-06-02 22:31:40', '2025-06-02 22:31:40'),
(2, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', '2023-12-26', '198.51.100.33', 'Approved', 'Approved the request', '2025-05-22 12:44:25', '2025-06-07 22:33:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `external_retreat_packenhams`
--

DROP TABLE IF EXISTS `external_retreat_packenhams`;
CREATE TABLE IF NOT EXISTS `external_retreat_packenhams` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_people` int NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_retreat_packenhams`
--

INSERT INTO `external_retreat_packenhams` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `number_of_people`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', 10, '175.157.37.164', 'Pending', NULL, '2025-03-01 13:10:33', '2025-06-02 22:39:35', '2025-06-02 22:39:35'),
(2, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', 10, '203.0.113.55', 'Approved', 'Approved the request', '2025-05-22 12:46:04', '2025-06-07 22:36:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `external_retreat_request_form__glen_waverleys`
--

DROP TABLE IF EXISTS `external_retreat_request_form__glen_waverleys`;
CREATE TABLE IF NOT EXISTS `external_retreat_request_form__glen_waverleys` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_people` int NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_retreat_request_form__glen_waverleys`
--

INSERT INTO `external_retreat_request_form__glen_waverleys` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `number_of_people`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', 10, '175.157.37.164', 'Pending', NULL, '2025-03-01 12:01:34', '2025-06-02 22:25:29', '2025-06-02 22:25:29'),
(2, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', 10, '203.0.113.10', 'Approved', 'Approved the request', '2025-05-22 12:42:50', '2025-06-07 22:30:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `five_year_requests`
--

DROP TABLE IF EXISTS `five_year_requests`;
CREATE TABLE IF NOT EXISTS `five_year_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street_address_line_1` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street_address_line_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `5_land_plots` tinyint(1) NOT NULL DEFAULT '0',
  `10_land_plots` tinyint(1) NOT NULL DEFAULT '0',
  `20_land_plots` tinyint(1) NOT NULL DEFAULT '0',
  `50_land_plots` tinyint(1) NOT NULL DEFAULT '0',
  `query` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `five_year_requests`
--

INSERT INTO `five_year_requests` (`id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street_address_line_1`, `street_address_line_2`, `city`, `postal_code`, `country`, `mobile_number`, `wt_number`, `email`, `5_land_plots`, `10_land_plots`, `20_land_plots`, `50_land_plots`, `query`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe Updated', '1990-01-01', 'Male', '123 Main St Updated', 'Apt 4B', 'Melbourne', '3000', 'Australia', '0771234567', 'WT123', 'john.doe.updated@example.com', 0, 1, 0, 0, 'Updated query about the 10 land plots option', '175.157.37.164', 'Pending', NULL, '2025-03-01 23:29:39', '2025-06-02 23:12:01', '2025-06-02 23:12:01'),
(2, 'John', 'Doe Updated', '1990-01-01', 'Male', '123 Main St Updated', 'Apt 4B', 'Melbourne', '3000', 'Australia', '0771234567', 'WT123', 'john.doe.updated@example.com', 0, 1, 0, 0, 'Updated query about the 10 land plots option', '175.157.37.164', 'Approved', 'Approved the request', '2025-03-01 23:36:24', '2025-06-07 22:41:31', NULL),
(3, 'James', 'Anderson', '1990-05-15', 'Male', '456 Oak Avenue', 'Apt 3B', 'Kandy', '20000', 'Sri Lanka', '9876543210', '5566778899', 'j.anderson@example.com', 1, 0, 1, 0, 'Can I visit the land plots before deciding?', '203.0.113.77', 'Pending', NULL, '2025-05-22 12:48:24', '2025-05-22 12:48:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `future_plans_request_forms`
--

DROP TABLE IF EXISTS `future_plans_request_forms`;
CREATE TABLE IF NOT EXISTS `future_plans_request_forms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contribute` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inquire` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `future_plans_request_forms`
--

INSERT INTO `future_plans_request_forms` (`id`, `first_name`, `last_name`, `address`, `city`, `postal_code`, `mobile_number`, `wt_number`, `email`, `contribute`, `inquire`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe Updated', '123 Main St Updated', 'Melbourne', '3000', '0771234567', 'WT123', 'john.doe.updated@example.com', 'I want to contribute with my time', 'Looking for volunteer opportunities', '175.157.37.164', 'Pending', NULL, '2025-03-01 14:02:56', '2025-06-02 22:43:36', '2025-06-02 22:43:36'),
(2, 'John', 'Doe', '123 Main St', 'New York', '10001', '1234567890', 'WT12345', 'john.doe@example.com', 'I want to contribute to future projects', 'I have questions about upcoming plans', '175.157.37.164', 'Approved', 'Approved the request', '2025-03-01 14:03:13', '2025-06-07 22:39:12', NULL),
(3, 'Sarah', 'Johnson', '123 Main Street', 'Colombo', '10000', '9876543210', '1122334455', 'sarah.j@example.com', 'Yes', 'About volunteer opportunities', '203.0.113.66', 'Pending', NULL, '2025-05-22 12:47:05', '2025-05-22 12:47:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gilan_pasa_requests`
--

DROP TABLE IF EXISTS `gilan_pasa_requests`;
CREATE TABLE IF NOT EXISTS `gilan_pasa_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gilan_pasa_requests`
--

INSERT INTO `gilan_pasa_requests` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `date`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', '2023-12-26', '175.157.37.164', 'Pending', NULL, '2025-03-02 00:15:51', '2025-06-02 23:21:25', '2025-06-02 23:21:25'),
(2, 'John', 'Doe Updated', '0771234567', 'WT123', 'john.doe.updated@example.com', '2023-12-26', '198.51.100.44', 'Approved', 'Approved the request', '2025-05-22 12:49:20', '2025-06-07 22:44:22', NULL),
(3, 'Lisa', 'Taylor', '6543210987', '7788990011', 'l.taylor@example.com', '2025-08-25', '198.51.100.44', 'Pending', NULL, '2025-06-07 22:44:45', '2025-06-07 22:44:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2025_01_17_191855_create_audit_admin_logs_table', 1),
(11, '2025_02_11_191138_add_coordinator_fields_to_users_table', 1),
(12, '2025_02_16_075306_create_sermon_requests_table', 1),
(13, '2025_02_16_100658_create_user_logs_table', 1),
(14, '2025_02_16_160729_create_dana_at_homes_table', 2),
(15, '2025_03_01_130201_create_dana_payment_requests_table', 3),
(16, '2025_03_01_171325_create_dana_requests_table', 4),
(17, '2025_03_01_172222_create_external_retreat_request_form__glen_waverleys_table', 5),
(18, '2025_03_01_173315_create_external_retreat_hallams_table', 6),
(19, '2025_03_01_182239_create_external_retreat_packenhams_table', 7),
(20, '2025_03_01_184508_create_future_plans_request_forms_table', 8),
(21, '2025_03_02_041101_create_five_year_requests_table', 9),
(22, '2025_03_02_053711_create_gilan_pasa_requests_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(11, 'App\\Models\\User', 1, 'LaravelAuthApp', '3fb5b52d9d0690c3dd3811b51eb9f5fcbe7ce744204b7cd7490d0f22516c8b17', '[\"*\"]', '2025-06-07 22:44:22', NULL, '2025-06-07 21:55:14', '2025-06-07 22:44:22'),
(4, 'App\\Models\\User', 2, 'LaravelAuthApp', '5a858a741b832f538e1d10704aef3f254e82b907a287f2be32293a755249c28b', '[\"*\"]', NULL, NULL, '2025-05-22 00:45:46', '2025-05-22 00:45:46');

-- --------------------------------------------------------

--
-- Table structure for table `sermon_requests`
--

DROP TABLE IF EXISTS `sermon_requests`;
CREATE TABLE IF NOT EXISTS `sermon_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` tinyint(1) NOT NULL DEFAULT '0',
  `sevenday` tinyint(1) NOT NULL DEFAULT '0',
  `warming` tinyint(1) NOT NULL DEFAULT '0',
  `threemonths` tinyint(1) NOT NULL DEFAULT '0',
  `oneyear` tinyint(1) NOT NULL DEFAULT '0',
  `annually` tinyint(1) NOT NULL DEFAULT '0',
  `weddings` tinyint(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','On hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sermon_requests`
--

INSERT INTO `sermon_requests` (`id`, `first_name`, `last_name`, `mobile_number`, `wt_number`, `email`, `date`, `time`, `count`, `option`, `birthday`, `sevenday`, `warming`, `threemonths`, `oneyear`, `annually`, `weddings`, `ip_address`, `status`, `status_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'John', 'Doe', '1234567890', 'WT12345', 'john.doe@example.com', '2023-12-25', '10:00 AM', '5', 'Some option', 1, 0, 1, 0, 1, 0, 1, '175.157.153.162', 'Pending', NULL, '2025-02-16 05:35:25', '2025-06-02 11:44:01', '2025-06-02 11:44:01'),
(2, 'John', 'Doe', '9876543210', '1234567890', 'john.doe@example.com', '2025-03-10', '10.20', NULL, NULL, 1, 1, 0, 0, 0, 0, 0, '203.0.113.4', 'Approved', 'Approved the request', '2025-05-22 02:44:02', '2025-06-07 22:07:02', NULL),
(3, 'John', 'Doe', '9876543210', '1234567890', 'john.doe@example.com', '2025-03-10', '10.20', NULL, NULL, 1, 1, 0, 0, 0, 0, 0, '203.0.113.4', 'Pending', NULL, '2025-05-22 12:23:58', '2025-05-22 12:23:58', NULL),
(4, 'John', 'Doe', '1234567890', 'WT12345', 'john.doe@example.com', '2023-12-25', '10:00 AM', '5', 'Some option', 1, 0, 1, 0, 1, 0, 1, '203.0.113.4', 'Pending', NULL, '2025-06-06 21:36:23', '2025-06-06 21:36:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_events_coordinator` tinyint NOT NULL DEFAULT '0',
  `is_community_service_coordinator` tinyint NOT NULL DEFAULT '0',
  `is_dana_coordinator` tinyint NOT NULL DEFAULT '0',
  `is_meditate_with_us_coordinator` tinyint NOT NULL DEFAULT '0',
  `is_dhamma_talks_coordinator` tinyint NOT NULL DEFAULT '0',
  `is_arama_poojawa_coordinator` tinyint NOT NULL DEFAULT '0',
  `is_build_up_hermitage_coordinator` tinyint NOT NULL DEFAULT '0',
  `is_donation_coordinator` tinyint NOT NULL DEFAULT '0',
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nic` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`, `is_events_coordinator`, `is_community_service_coordinator`, `is_dana_coordinator`, `is_meditate_with_us_coordinator`, `is_dhamma_talks_coordinator`, `is_arama_poojawa_coordinator`, `is_build_up_hermitage_coordinator`, `is_donation_coordinator`, `gender`, `nic`, `deleted_at`) VALUES
(1, 'John admin', 'admin@admin.com', '2025-02-16 05:34:31', '$2y$10$KnI84BjW127pLI0WZzj5UeEUbCP13C63Q.k0vNCig5HbB.fG2zQHi', 'ADMIN', NULL, '2025-02-16 05:34:31', '2025-02-16 05:34:31', 1, 1, 1, 1, 1, 1, 1, 1, 'male', '123456789V', NULL),
(2, 'Joshn Doe', 'workgen353@gmail.com', NULL, '$2y$10$caCIBh43ZSQ6BIQxXSrX0.ZZ7WvEHzlHOa/rdcbf.VSY.HvjH9.Zu', 'Coordinator', NULL, '2025-05-22 00:38:03', '2025-05-22 00:38:03', 1, 1, 1, 1, 1, 1, 0, 1, 'Male', '12342526789V', NULL),
(3, 'Joshn Doe', 'workgen3523@gmail.com', NULL, '$2y$10$YReYFns6gbGF8tZNxxU/cepc52cDiSUq3N5PALRv07/93x2EObgDe', 'Coordinator', NULL, '2025-06-02 10:54:31', '2025-06-02 10:54:31', 1, 1, 1, 1, 1, 1, 0, 1, 'Male', '12342516789V', NULL),
(4, 'Joshn Doe', 'workgen3523w@gmail.com', NULL, '$2y$10$OPGiwtjsGR9XzlstoQsFNeBbUXSjIMNIr7eHtxXI/jyXeHwcJOjyq', 'Coordinator', NULL, '2025-06-02 11:05:57', '2025-06-02 11:09:45', 1, 1, 1, 1, 1, 1, 0, 1, 'Male', '12342116789V', '2025-06-02 11:09:45');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `form_id` bigint UNSIGNED DEFAULT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guest',
  `action_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_date_time` datetime NOT NULL,
  `entity_area` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `form_id`, `user_name`, `user_role`, `action_type`, `action_date_time`, `entity_area`, `old_values`, `new_values`, `description`, `ip_address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 'Guest', 'Guest', 'form_submission', '2025-03-01 13:41:04', 'Dana Payment Request', NULL, '{\"id\": 1, \"email\": \"john.doe@example.com\", \"last_name\": \"da\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-01T13:41:04.000000Z\", \"first_name\": \"das\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-01T13:41:04.000000Z\", \"mobile_number\": \"0789621706\", \"dana_for_lunch\": false, \"dana_event_date\": \"2010/04/20\", \"dana_for_morning\": true}', 'das submitted a Dana Payment Request. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 08:11:04', '2025-03-01 08:11:04', NULL),
(2, NULL, 1, 'Guest', 'Guest', 'form_submission', '2025-03-01 17:19:51', 'Dana Request', NULL, '{\"id\": 1, \"email\": \"string@gmail.com\", \"last_name\": \"las\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-01T17:19:51.000000Z\", \"first_name\": \"test\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-01T17:19:51.000000Z\", \"mobile_number\": \"0789621706\", \"dana_event_date\": \"2010/04/20\"}', 'test submitted a Dana Request. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 11:49:51', '2025-03-01 11:49:51', NULL),
(3, NULL, 1, 'Guest', 'Guest', 'form_submission', '2025-03-01 17:31:34', 'External Retreat Request Form Glen Waverley', NULL, '{\"id\": 1, \"email\": \"string@gmail.com\", \"last_name\": \"dasdas\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-01T17:31:34.000000Z\", \"first_name\": \"sadasd\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-01T17:31:34.000000Z\", \"mobile_number\": \"0789621706\", \"number_of_people\": \"10\"}', 'sadasd submitted a External Retreat Request Form Glen Waverley. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 12:01:34', '2025-03-01 12:01:34', NULL),
(4, NULL, 1, 'Guest', 'Guest', 'form_submission', '2025-03-01 17:44:49', 'External Retreat Request Form Hallam', NULL, '{\"id\": 1, \"date\": \"2010/04/20\", \"email\": \"string@gmail.com\", \"last_name\": \"da\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-01T17:44:49.000000Z\", \"first_name\": \"dasdas\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-01T17:44:49.000000Z\", \"mobile_number\": \"0789621706\"}', 'dasdas submitted a External Retreat Request Form Hallam. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 12:14:49', '2025-03-01 12:14:49', NULL),
(5, NULL, 1, 'Guest', 'Guest', 'form_submission', '2025-03-01 18:40:33', 'External Retreat Request Form Packenham', NULL, '{\"id\": 1, \"email\": \"adweb@gmail.com\", \"last_name\": \"das\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-01T18:40:33.000000Z\", \"first_name\": \"das\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-01T18:40:33.000000Z\", \"mobile_number\": \"0789621706\", \"number_of_people\": \"10\"}', 'das submitted a External Retreat Request Form Packenham. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 13:10:33', '2025-03-01 13:10:33', NULL),
(6, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-03-01 19:33:13', 'Future Plans Request Form', NULL, '{\"id\": 2, \"city\": \"colomo\", \"email\": \"john.doe@example.com\", \"address\": \"dasda\", \"inquire\": \"yres\", \"last_name\": \"dasdas\", \"wt_number\": \"0789621706\", \"contribute\": \"noo\", \"created_at\": \"2025-03-01T19:33:13.000000Z\", \"first_name\": \"dasdas\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-01T19:33:13.000000Z\", \"postal_code\": \"10300\", \"mobile_number\": \"0789621706\"}', 'dasdas submitted a Future Plans Request Form. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 14:03:13', '2025-03-01 14:03:13', NULL),
(7, NULL, 1, 'Guest', 'Guest', 'form_submission', '2025-03-02 04:59:39', 'Five Year Request Form', NULL, '{\"id\": 1, \"city\": null, \"email\": \"string@gmail.com\", \"query\": null, \"gender\": \"Female\", \"country\": null, \"last_name\": \"dsadas\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-02T04:59:39.000000Z\", \"first_name\": \"dasdas\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-02T04:59:39.000000Z\", \"postal_code\": null, \"5_land_plots\": false, \"10_land_plots\": false, \"20_land_plots\": false, \"50_land_plots\": false, \"date_of_birth\": \"2025-03-02\", \"mobile_number\": \"0789621706\", \"street_address_line_1\": \"dasdasd\", \"street_address_line_2\": null}', 'dasdas submitted a Five Year Request Form. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 23:29:39', '2025-03-01 23:29:39', NULL),
(8, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-03-02 05:06:24', 'Five Year Request Form', NULL, '{\"id\": 2, \"city\": \"fdsfsd\", \"email\": \"string@gmail.com\", \"query\": null, \"gender\": \"Female\", \"country\": \"fsdfsd\", \"last_name\": \"fsdfsd\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-02T05:06:24.000000Z\", \"first_name\": \"fdsf\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-02T05:06:24.000000Z\", \"postal_code\": \"fdsf\", \"5_land_plots\": true, \"10_land_plots\": false, \"20_land_plots\": false, \"50_land_plots\": false, \"date_of_birth\": \"2025-03-02\", \"mobile_number\": \"0789621706\", \"street_address_line_1\": \"fdsfsd\", \"street_address_line_2\": null}', 'fdsf submitted a Five Year Request Form. Mobile number is 0789621706', '127.0.0.1', '2025-03-01 23:36:24', '2025-03-01 23:36:24', NULL),
(9, NULL, 1, 'Guest', 'Guest', 'form_submission', '2025-03-02 05:45:51', 'Gilan Pasa Request', NULL, '{\"id\": 1, \"date\": \"2010/04/20\", \"email\": \"adweb@gmail.com\", \"last_name\": \"Suranga\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-03-02T05:45:51.000000Z\", \"first_name\": \"Charitha\", \"ip_address\": \"175.157.37.164\", \"updated_at\": \"2025-03-02T05:45:51.000000Z\", \"mobile_number\": \"0789621706\"}', 'Charitha submitted a Gilan Pasa Request. Mobile number is 0789621706', '127.0.0.1', '2025-03-02 00:15:51', '2025-03-02 00:15:51', NULL),
(10, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 08:14:02', 'Damma Sermons Request', NULL, '{\"id\": 2, \"date\": \"2025-03-10\", \"time\": \"10.20\", \"count\": null, \"email\": \"john.doe@example.com\", \"option\": null, \"oneyear\": \"0\", \"warming\": \"0\", \"annually\": \"0\", \"birthday\": \"1\", \"sevenday\": \"1\", \"weddings\": \"0\", \"last_name\": \"Doe\", \"wt_number\": \"1234567890\", \"created_at\": \"2025-05-22T08:14:02.000000Z\", \"first_name\": \"John\", \"ip_address\": \"203.0.113.4\", \"updated_at\": \"2025-05-22T08:14:02.000000Z\", \"threemonths\": \"0\", \"mobile_number\": \"9876543210\"}', 'John submitted a Damma Sermons Request. Mobile number is 9876543210', '127.0.0.1', '2025-05-22 02:44:02', '2025-05-22 02:44:02', NULL),
(11, NULL, 3, 'Guest', 'Guest', 'form_submission', '2025-05-22 17:53:58', 'Damma Sermons Request', NULL, '{\"id\": 3, \"date\": \"2025-03-10\", \"time\": \"10.20\", \"count\": null, \"email\": \"john.doe@example.com\", \"option\": null, \"oneyear\": \"0\", \"warming\": \"0\", \"annually\": \"0\", \"birthday\": \"1\", \"sevenday\": \"1\", \"weddings\": \"0\", \"last_name\": \"Doe\", \"wt_number\": \"1234567890\", \"created_at\": \"2025-05-22T17:53:58.000000Z\", \"first_name\": \"John\", \"ip_address\": \"203.0.113.4\", \"updated_at\": \"2025-05-22T17:53:58.000000Z\", \"threemonths\": \"0\", \"mobile_number\": \"9876543210\"}', 'John submitted a Damma Sermons Request. Mobile number is 9876543210', '127.0.0.1', '2025-05-22 12:23:58', '2025-05-22 12:23:58', NULL),
(12, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:00:42', 'Dana At Home Request', NULL, '{\"id\": 2, \"email\": \"john.doe@example.com\", \"other\": \"Special dietary requirements\", \"oneyear\": \"1\", \"warming\": \"1\", \"annually\": \"0\", \"birthday\": \"1\", \"sevenday\": \"0\", \"weddings\": \"1\", \"last_name\": \"Doe\", \"wt_number\": \"1234567890\", \"created_at\": \"2025-05-22T18:00:42.000000Z\", \"first_name\": \"John\", \"ip_address\": \"203.0.113.4\", \"updated_at\": \"2025-05-22T18:00:42.000000Z\", \"threemonths\": \"0\", \"mobile_number\": \"9876543210\", \"dana_for_lunch\": \"1\", \"specific_event\": \"Annual Ceremony\", \"dana_for_morning\": \"0\"}', 'John submitted a Dana At Home Request. Mobile number is 9876543210', '127.0.0.1', '2025-05-22 12:30:42', '2025-05-22 12:30:42', NULL),
(13, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:03:34', 'Dana Payment Request', NULL, '{\"id\": 2, \"email\": \"alice.smith@example.com\", \"last_name\": \"Smith\", \"wt_number\": \"1122334455\", \"created_at\": \"2025-05-22T18:03:34.000000Z\", \"first_name\": \"Alice\", \"ip_address\": \"203.0.113.45\", \"updated_at\": \"2025-05-22T18:03:34.000000Z\", \"mobile_number\": \"9876543211\", \"dana_for_lunch\": \"1\", \"dana_event_date\": \"2025-05-25\", \"dana_for_morning\": \"0\"}', 'Alice submitted a Dana Payment Request. Mobile number is 9876543211', '127.0.0.1', '2025-05-22 12:33:34', '2025-05-22 12:33:34', NULL),
(14, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:04:35', 'Dana Request', NULL, '{\"id\": 2, \"email\": \"bob.johnson@example.com\", \"last_name\": \"Johnson\", \"wt_number\": \"0789621706\", \"created_at\": \"2025-05-22T18:04:35.000000Z\", \"first_name\": \"Bob\", \"ip_address\": \"198.51.100.22\", \"updated_at\": \"2025-05-22T18:04:35.000000Z\", \"mobile_number\": \"8765432109\", \"dana_event_date\": \"2025-06-15\"}', 'Bob submitted a Dana Request. Mobile number is 8765432109', '127.0.0.1', '2025-05-22 12:34:35', '2025-05-22 12:34:35', NULL),
(15, NULL, 3, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:08:43', 'Dana Request', NULL, '{\"id\": 3, \"email\": \"bob.johnson@example.com\", \"last_name\": \"Johnson\", \"wt_number\": \"0789617444\", \"created_at\": \"2025-05-22T18:08:43.000000Z\", \"first_name\": \"Bob\", \"ip_address\": \"198.51.100.22\", \"updated_at\": \"2025-05-22T18:08:43.000000Z\", \"mobile_number\": \"8765432109\", \"dana_event_date\": \"2025-06-15\"}', 'Bob submitted a Dana Request. Mobile number is 8765432109', '127.0.0.1', '2025-05-22 12:38:43', '2025-05-22 12:38:43', NULL),
(16, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:12:50', 'External Retreat Request Form Glen Waverley', NULL, '{\"id\": 2, \"email\": \"emma.watson@example.com\", \"last_name\": \"Watson\", \"wt_number\": \"1234567890\", \"created_at\": \"2025-05-22T18:12:50.000000Z\", \"first_name\": \"Emma\", \"ip_address\": \"203.0.113.10\", \"updated_at\": \"2025-05-22T18:12:50.000000Z\", \"mobile_number\": \"9876543210\", \"number_of_people\": \"5\"}', 'Emma submitted a External Retreat Request Form Glen Waverley. Mobile number is 9876543210', '127.0.0.1', '2025-05-22 12:42:50', '2025-05-22 12:42:50', NULL),
(17, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:14:25', 'External Retreat Request Form Hallam', NULL, '{\"id\": 2, \"date\": \"2025-07-20\", \"email\": \"michael.b@example.com\", \"last_name\": \"Brown\", \"wt_number\": \"0789614444\", \"created_at\": \"2025-05-22T18:14:25.000000Z\", \"first_name\": \"Michael\", \"ip_address\": \"198.51.100.33\", \"updated_at\": \"2025-05-22T18:14:25.000000Z\", \"mobile_number\": \"8765432109\"}', 'Michael submitted a External Retreat Request Form Hallam. Mobile number is 8765432109', '127.0.0.1', '2025-05-22 12:44:25', '2025-05-22 12:44:25', NULL),
(18, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:16:04', 'External Retreat Request Form Packenham', NULL, '{\"id\": 2, \"email\": \"d.wilson@example.com\", \"last_name\": \"Wilson\", \"wt_number\": \"3344556677\", \"created_at\": \"2025-05-22T18:16:04.000000Z\", \"first_name\": \"David\", \"ip_address\": \"203.0.113.55\", \"updated_at\": \"2025-05-22T18:16:04.000000Z\", \"mobile_number\": \"7654321098\", \"number_of_people\": \"2\"}', 'David submitted a External Retreat Request Form Packenham. Mobile number is 7654321098', '127.0.0.1', '2025-05-22 12:46:04', '2025-05-22 12:46:04', NULL),
(19, NULL, 3, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:17:05', 'Future Plans Request Form', NULL, '{\"id\": 3, \"city\": \"Colombo\", \"email\": \"sarah.j@example.com\", \"address\": \"123 Main Street\", \"inquire\": \"About volunteer opportunities\", \"last_name\": \"Johnson\", \"wt_number\": \"1122334455\", \"contribute\": \"Yes\", \"created_at\": \"2025-05-22T18:17:05.000000Z\", \"first_name\": \"Sarah\", \"ip_address\": \"203.0.113.66\", \"updated_at\": \"2025-05-22T18:17:05.000000Z\", \"postal_code\": \"10000\", \"mobile_number\": \"9876543210\"}', 'Sarah submitted a Future Plans Request Form. Mobile number is 9876543210', '127.0.0.1', '2025-05-22 12:47:05', '2025-05-22 12:47:05', NULL),
(20, NULL, 3, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:18:24', 'Five Year Request Form', NULL, '{\"id\": 3, \"city\": \"Kandy\", \"email\": \"j.anderson@example.com\", \"query\": \"Can I visit the land plots before deciding?\", \"gender\": \"Male\", \"country\": \"Sri Lanka\", \"last_name\": \"Anderson\", \"wt_number\": \"5566778899\", \"created_at\": \"2025-05-22T18:18:24.000000Z\", \"first_name\": \"James\", \"ip_address\": \"203.0.113.77\", \"updated_at\": \"2025-05-22T18:18:24.000000Z\", \"postal_code\": \"20000\", \"5_land_plots\": \"1\", \"10_land_plots\": \"0\", \"20_land_plots\": \"1\", \"50_land_plots\": \"0\", \"date_of_birth\": \"1990-05-15\", \"mobile_number\": \"9876543210\", \"street_address_line_1\": \"456 Oak Avenue\", \"street_address_line_2\": \"Apt 3B\"}', 'James submitted a Five Year Request Form. Mobile number is 9876543210', '127.0.0.1', '2025-05-22 12:48:24', '2025-05-22 12:48:24', NULL),
(21, NULL, 2, 'Guest', 'Guest', 'form_submission', '2025-05-22 18:19:20', 'Gilan Pasa Request', NULL, '{\"id\": 2, \"date\": \"2025-08-25\", \"email\": \"l.taylor@example.com\", \"last_name\": \"Taylor\", \"wt_number\": \"7788990011\", \"created_at\": \"2025-05-22T18:19:20.000000Z\", \"first_name\": \"Lisa\", \"ip_address\": \"198.51.100.44\", \"updated_at\": \"2025-05-22T18:19:20.000000Z\", \"mobile_number\": \"6543210987\"}', 'Lisa submitted a Gilan Pasa Request. Mobile number is 6543210987', '127.0.0.1', '2025-05-22 12:49:20', '2025-05-22 12:49:20', NULL),
(22, NULL, 4, 'Guest', 'Guest', 'form_submission', '2025-06-07 03:06:23', 'Damma Sermons Request', NULL, '{\"id\": 4, \"date\": \"2025-03-10\", \"time\": \"10.20\", \"count\": null, \"email\": \"john.doe@example.com\", \"option\": null, \"oneyear\": \"0\", \"warming\": \"0\", \"annually\": \"0\", \"birthday\": \"1\", \"sevenday\": \"1\", \"weddings\": \"0\", \"last_name\": \"Doe\", \"wt_number\": \"1234567890\", \"created_at\": \"2025-06-07T03:06:23.000000Z\", \"first_name\": \"John\", \"ip_address\": \"203.0.113.4\", \"updated_at\": \"2025-06-07T03:06:23.000000Z\", \"threemonths\": \"0\", \"mobile_number\": \"9876543210\"}', 'John submitted a Damma Sermons Request. Mobile number is 9876543210', '127.0.0.1', '2025-06-06 21:36:23', '2025-06-06 21:36:23', NULL),
(23, NULL, 3, 'Guest', 'Guest', 'form_submission', '2025-06-08 04:14:45', 'Gilan Pasa Request', NULL, '{\"id\": 3, \"date\": \"2025-08-25\", \"email\": \"l.taylor@example.com\", \"last_name\": \"Taylor\", \"wt_number\": \"7788990011\", \"created_at\": \"2025-06-08T04:14:45.000000Z\", \"first_name\": \"Lisa\", \"ip_address\": \"198.51.100.44\", \"updated_at\": \"2025-06-08T04:14:45.000000Z\", \"mobile_number\": \"6543210987\"}', 'Lisa submitted a Gilan Pasa Request. Mobile number is 6543210987', '127.0.0.1', '2025-06-07 22:44:45', '2025-06-07 22:44:45', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
