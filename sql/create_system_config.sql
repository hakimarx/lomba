-- System Configuration Table for Musabaqah Application
-- Run this SQL in phpMyAdmin or MySQL CLI

CREATE TABLE IF NOT EXISTS `system_config` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `config_key` VARCHAR(100) NOT NULL UNIQUE,
  `config_value` TEXT,
  `description` VARCHAR(255),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default configuration values
INSERT INTO `system_config` (`config_key`, `config_value`, `description`) VALUES
('mushaf_source', 'offline', 'Sumber gambar mushaf: offline (lokal) atau online (API)'),
('mushaf_api_indonesia', 'https://everyayah.com/data/images_png/', 'URL API untuk mushaf Indonesia'),
('mushaf_api_madinah', 'https://everyayah.com/data/images_png/', 'URL API untuk mushaf Madinah')
ON DUPLICATE KEY UPDATE `config_key` = VALUES(`config_key`);
