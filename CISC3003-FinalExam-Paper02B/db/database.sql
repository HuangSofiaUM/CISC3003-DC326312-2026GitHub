-- ==========================================
-- Scenario B database setup
-- ==========================================
CREATE DATABASE IF NOT EXISTS cisc3003_exam_b
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE cisc3003_exam_b;

CREATE TABLE IF NOT EXISTS contact_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message_body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
