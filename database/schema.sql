-- Qyzylorda Times Database Schema
-- Схема базы данных для новостного портала

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Таблица пользователей (Администраторы)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100),
    `role` ENUM('admin', 'editor') DEFAULT 'editor',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица категорий
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name_kz` VARCHAR(100) NOT NULL,
    `name_ru` VARCHAR(100) NOT NULL,
    `slug_kz` VARCHAR(100) UNIQUE NOT NULL,
    `slug_ru` VARCHAR(100) UNIQUE NOT NULL,
    `description_kz` TEXT,
    `description_ru` TEXT,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_slug_kz` (`slug_kz`),
    INDEX `idx_slug_ru` (`slug_ru`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица постов (Двуязычные новостные статьи)
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `category_id` INT,
    `user_id` INT,
    
    -- Казахская версия
    `title_kz` VARCHAR(255) NOT NULL,
    `slug_kz` VARCHAR(255) UNIQUE NOT NULL,
    `content_kz` TEXT NOT NULL,
    `excerpt_kz` VARCHAR(500),
    
    -- Русская версия
    `title_ru` VARCHAR(255),
    `slug_ru` VARCHAR(255),
    `content_ru` TEXT,
    `excerpt_ru` VARCHAR(500),
    
    -- Общие поля
    `image` VARCHAR(255),
    `views` INT DEFAULT 0,
    `status` ENUM('draft', 'published') DEFAULT 'draft',
    `is_featured` BOOLEAN DEFAULT FALSE,
    `is_announcement` BOOLEAN DEFAULT FALSE,
    `published_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_slug_kz` (`slug_kz`),
    INDEX `idx_slug_ru` (`slug_ru`),
    INDEX `idx_status` (`status`),
    INDEX `idx_published_at` (`published_at`),
    INDEX `idx_views` (`views`),
    INDEX `idx_featured` (`is_featured`),
    INDEX `idx_announcement` (`is_announcement`),
    FULLTEXT `idx_search_kz` (`title_kz`, `content_kz`),
    FULLTEXT `idx_search_ru` (`title_ru`, `content_ru`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица комментариев (с пре-модерацией)
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `post_id` INT NOT NULL,
    `author_name` VARCHAR(100) NOT NULL,
    `comment_text` TEXT NOT NULL,
    `author_ip` VARCHAR(45),
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `moderated_at` TIMESTAMP NULL,
    `moderated_by` INT NULL,
    
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`moderated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_post_status` (`post_id`, `status`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица настроек
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица кеша погоды
DROP TABLE IF EXISTS `weather_cache`;
CREATE TABLE `weather_cache` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `temperature` DECIMAL(5,2),
    `condition` VARCHAR(50),
    `icon` VARCHAR(50),
    `cached_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица кеша валют
DROP TABLE IF EXISTS `currency_cache`;
CREATE TABLE `currency_cache` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `usd_rate` DECIMAL(10,4),
    `eur_rate` DECIMAL(10,4),
    `rub_rate` DECIMAL(10,4),
    `cached_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица тегов (для SEO ключевых слов)
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name_kz` VARCHAR(50) NOT NULL,
    `name_ru` VARCHAR(50),
    `slug` VARCHAR(50) UNIQUE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Связующая таблица Post-Tag
DROP TABLE IF EXISTS `post_tags`;
CREATE TABLE `post_tags` (
    `post_id` INT NOT NULL,
    `tag_id` INT NOT NULL,
    PRIMARY KEY (`post_id`, `tag_id`),
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
