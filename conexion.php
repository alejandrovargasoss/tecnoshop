<?php
// ✅ Funciona en LOCAL y PRODUCCIÓN
$host = getenv('DB_HOST')     ?: 'localhost';         // fallback local
$user = getenv('DB_USER')     ?: 'root';
$pass = getenv('DB_PASS')     ?: '';
$db   = getenv('DB_NAME')     ?: 'tecnoshop_db';
$port = getenv('DB_PORT')     ?: 3306;

$conn = new mysqli(
  $host, $user,
  $pass, $db, (int)$port
);

if ($conn->connect_error) {
  http_response_code(500);
  die(json_encode([
    "ok" => false,
    "error" => "DB connection failed"
  ]));
}

$conn->set_charset("utf8mb4");
header("Content-Type: application/json");
?>