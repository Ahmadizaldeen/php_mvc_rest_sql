DROP DATABASE phpd_mvc_rest;
CREATE DATABASE IF NOT EXISTS phpd_mvc_rest;
USE phpd_mvc_rest;
CREATE TABLE todos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,       
    description TEXT,                        
    status      ENUM('open','done') DEFAULT 'open',
    created_at  DATETIME DEFAULT NOW(),      
    updated_at  DATETIME DEFAULT NOW() ON UPDATE NOW(),
    deleted_at  DATETIME DEFAULT NULL        -- Soft Delete
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

