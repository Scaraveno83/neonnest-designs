<?php
// NeonNest Designs - DB Connection & Session

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DB configuration - bitte anpassen
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_453539_4');
define('DB_USER', 'USER453539_22');
define('DB_PASS', '15118329112006');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Datenbankverbindung fehlgeschlagen: ' . htmlspecialchars($e->getMessage()));
}
