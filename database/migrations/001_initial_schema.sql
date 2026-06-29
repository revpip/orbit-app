-- ORBIT app migration 001
-- Keep aligned with orbit-docs/database/schema-v0.1.sql

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(120) NOT NULL,
  status ENUM('pending','active','suspended','deleted') NOT NULL DEFAULT 'pending',
  email_verified_at DATETIME NULL,
  last_login_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE profiles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL UNIQUE,
  headline VARCHAR(160) NULL,
  bio TEXT NULL,
  date_of_birth DATE NULL,
  gender VARCHAR(60) NULL,
  connection_preferences JSON NULL,
  postcode_prefix VARCHAR(16) NULL,
  town VARCHAR(120) NULL,
  country_code CHAR(2) NOT NULL DEFAULT 'GB',
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  visibility ENUM('public','members','private') NOT NULL DEFAULT 'members',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
