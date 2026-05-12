-- ==========================================
-- Scenario C database setup
-- ==========================================
CREATE DATABASE IF NOT EXISTS cisc3003_exam_c
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE cisc3003_exam_c;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email_verification_token VARCHAR(120) DEFAULT NULL,
    email_verified_at DATETIME DEFAULT NULL,
    reset_token VARCHAR(120) DEFAULT NULL,
    reset_expires_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
