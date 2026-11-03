<?php
$pdo = new PDO('mysql:host=localhost;dbname=beta', 'root', '');
$sql = "CREATE TABLE IF NOT EXISTS projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    public_id VARCHAR(255) UNIQUE NOT NULL,
    title JSON NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category VARCHAR(255) NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    description JSON NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$pdo->exec($sql);
echo "Projects table created successfully\n";