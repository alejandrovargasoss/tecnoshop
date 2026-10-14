<?php
// Redirigir a HTTPS si la petición llega por HTTP
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'http') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
    exit();
}

// Cabeceras de seguridad
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Continuar con el resto del sistema...
/**
 * index.php — Front Controller TecnoShop
 * Único punto de entrada al sistema MVC
 * TecnoShop · SENA ADSO 
 */
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/controladores/ProductoControlador.php';
require_once __DIR__ . '/controladores/ClienteControlador.php';
require_once __DIR__ . '/controladores/VentaControlador.php';
require_once __DIR__ . '/controladores/ReporteControlador.php';
require_once __DIR__ . '/controladores/IAControlador.php';

$modulo = $_GET['modulo'] ?? 'dashboard';
$accion = $_GET['accion'] ?? 'listar';

switch ($modulo) {
    case 'productos':
        $ctrl = new ProductoControlador($conexion); break;
    case 'clientes':
        $ctrl = new ClienteControlador($conexion);  break;
    case 'ventas':
        $ctrl = new VentaControlador($conexion);    break;
    case 'reportes':
        $ctrl = new ReporteControlador($conexion);  break;
    case 'ia':
        $ctrl = new IAControlador($conexion);       break;
    case 'dashboard':
        header('Content-Type: text/html; charset=utf-8');
        require_once __DIR__ . '/vistas/layout/header.php';
        require_once __DIR__ . '/vistas/dashboard.php';
        require_once __DIR__ . '/vistas/layout/footer.php';
        exit;
    default:
        http_response_code(404);
        echo json_encode(['ok' => false, 'mensaje' => 'Módulo no encontrado']);
        exit;
}

if (method_exists($ctrl, $accion)) {
    $ctrl->$accion();
} else {
    http_response_code(404);
    echo json_encode([
        'ok'      => false,
        'mensaje' => "Acción '$accion' no encontrada en módulo '$modulo'",
    ]);
}
