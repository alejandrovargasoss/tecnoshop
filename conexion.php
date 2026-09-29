<?php
/**
 * conexion.php — Conexión MySQL + Cabeceras API REST
 * TecnoShop · SENA ADSO · Juan Carlos Huertas Niño
 */

// Cabeceras API REST
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// Leer credenciales desde .env o variables de entorno
$host    = getenv('DB_HOST')    ?: 'localhost';
$usuario = getenv('DB_USER')    ?: 'root';
$clave   = getenv('DB_PASS')    ?: '';
$base    = getenv('DB_NAME')    ?: 'tecnoshop_db';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$conexion = new mysqli($host, $usuario, $clave, $base);
$conexion->set_charset($charset);

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode([
        'ok'      => false,
        'mensaje' => 'Error de conexión a la base de datos',
    ]);
    exit;
}
