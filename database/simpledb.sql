-- BETA Industrial Final Database Schema (simpledb.sql)
-- Target: MySQL 8.0+ / phpMyAdmin / InnoDB / utf8mb4
-- Stores Arabic & English in JSON columns: {"ar": "...", "en": "..."}

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `attachments`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `quotations`;
DROP TABLE IF EXISTS `quote_requests`;
DROP TABLE IF EXISTS `job_applications`;
DROP TABLE IF EXISTS `job_postings`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `currencies`;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. REGIONAL CURRENCIES & COUNTRIES
CREATE TABLE `currencies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `code` CHAR(3) NOT NULL UNIQUE,
    `symbol` VARCHAR(12) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `countries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `iso2` CHAR(2) NOT NULL UNIQUE,
    `name` VARCHAR(120) NOT NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_countries_currency` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ADMINISTRATIVE USERS (Sales Team & Management)
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `name` VARCHAR(160) NOT NULL,
    `email` VARCHAR(254) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'sales',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. DISPLAY CATALOG (Services & Products with JSON Translatable Fields)
CREATE TABLE `services` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `title` JSON NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `description` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `title` JSON NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `description` JSON NULL,
    `specifications` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ANNOUNCEMENTS & NEWS
CREATE TABLE `announcements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `title` JSON NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `type` VARCHAR(50) NOT NULL DEFAULT 'news',
    `excerpt` JSON NULL,
    `content` JSON NOT NULL,
    `published_at` DATETIME NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. GUEST QUOTE REQUEST SYSTEM (No Client Accounts Required)
CREATE TABLE `quote_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `client_name` VARCHAR(150) NOT NULL,
    `company_name` VARCHAR(150) NULL,
    `email` VARCHAR(254) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `country_id` BIGINT UNSIGNED NULL,
    `service_id` BIGINT UNSIGNED NULL,
    `product_id` BIGINT UNSIGNED NULL,
    `client_message` TEXT NOT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'new',
    `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_quote_requests_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_quote_requests_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_quote_requests_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. ADMIN QUOTATIONS & PROFIT TRACKING
CREATE TABLE `quotations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `quote_request_id` BIGINT UNSIGNED NOT NULL,
    `admin_user_id` BIGINT UNSIGNED NOT NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `factory_cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `selling_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `profit_amount` DECIMAL(15,2) GENERATED ALWAYS AS (`selling_price` - `factory_cost`) STORED,
    `status` VARCHAR(50) NOT NULL DEFAULT 'sent_to_client',
    `admin_notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_quotations_request` FOREIGN KEY (`quote_request_id`) REFERENCES `quote_requests` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_quotations_admin` FOREIGN KEY (`admin_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_quotations_currency` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. INVOICING
CREATE TABLE `invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `quotation_id` BIGINT UNSIGNED NOT NULL,
    `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
    `total_amount` DECIMAL(15,2) NOT NULL,
    `payment_status` VARCHAR(50) NOT NULL DEFAULT 'unpaid',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_invoices_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. CAREERS & JOB APPLICATIONS
CREATE TABLE `job_postings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `title` JSON NOT NULL,
    `country_id` BIGINT UNSIGNED NULL,
    `description` JSON NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_job_postings_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_applications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `job_posting_id` BIGINT UNSIGNED NOT NULL,
    `applicant_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(254) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `rating` TINYINT UNSIGNED NULL DEFAULT 0,
    `status` VARCHAR(50) NOT NULL DEFAULT 'new',
    `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_applications_job` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. POLYMORPHIC SECURE ATTACHMENTS
CREATE TABLE `attachments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` CHAR(26) NOT NULL UNIQUE,
    `attachable_type` VARCHAR(100) NOT NULL,
    `attachable_id` BIGINT UNSIGNED NOT NULL,
    `file_path` VARCHAR(512) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(100) NULL,
    `size_bytes` BIGINT UNSIGNED NULL,
    `scan_status` VARCHAR(50) NOT NULL DEFAULT 'passed',
    `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_attachments_polymorphic` (`attachable_type`, `attachable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. SYSTEM AUDIT LOGS
CREATE TABLE `audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `actor_user_id` BIGINT UNSIGNED NULL,
    `event_type` VARCHAR(80) NOT NULL,
    `model_type` VARCHAR(160) NULL,
    `model_id` VARCHAR(80) NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_audit_logs_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEED DATA (Egypt & Saudi Arabia Only)
INSERT INTO `currencies` (`code`, `symbol`, `is_active`) VALUES
('EGP', 'EGP', 1),
('SAR', 'SAR', 1);

INSERT INTO `countries` (`iso2`, `name`, `currency_id`, `is_active`) VALUES
('EG', 'Egypt', 1, 1),
('SA', 'Saudi Arabia', 2, 1);