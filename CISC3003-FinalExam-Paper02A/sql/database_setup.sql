-- =========================================================
-- A.09 Create database and table (run in phpMyAdmin SQL tab)
-- =========================================================
CREATE DATABASE IF NOT EXISTS cisc3003_exam
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE cisc3003_exam;

CREATE TABLE IF NOT EXISTS student_profiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    age INT NOT NULL,
    bio TEXT NOT NULL,
    programme VARCHAR(30) NOT NULL,
    contact_method VARCHAR(20) NOT NULL,
    skills VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- A.10 SQL INSERT INTO statement example
-- =========================================================
INSERT INTO student_profiles (full_name, email, age, bio, programme, contact_method, skills)
VALUES (
    'Sample Student',
    'sample@student.um.edu.mo',
    20,
    'This sample record is inserted using a plain SQL INSERT statement.',
    'CS',
    'email',
    'php, mysql, htmlcss'
);
