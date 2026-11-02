<?php
// Configure DB_HOST, DB_NAME, DB_USER, and DB_PASSWORD on your local PHP server.
// No database credentials are included in this public source repository.
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

if ($db === false || $user === false || $pass === false) {
    http_response_code(503);
    exit('Database not configured. Set DB_NAME, DB_USER, and DB_PASSWORD.');
}

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $options);
