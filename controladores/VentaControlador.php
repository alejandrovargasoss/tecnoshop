<?php
require_once __DIR__ . '/../modelos/VentaModelo.php';
class VentaControlador {
    private $modelo;
    public function __construct($bd) { $this->modelo = new VentaModelo($bd); }

    public function listar() {
        header('Content-Type: text/html; charset=utf-8');
        require_once __DIR__ . '/../vistas/layout/header.php';
        require_once __DIR__ . '/../vistas/ventas/lista.php';
        require_once __DIR__ . '/../vistas/layout/footer.php';
    }

    public function obtenerTodos() {
        $datos = $this->modelo->obtenerTodos();
        echo json_encode(['ok'=>true,'datos'=>$datos,'total'=>count($datos)]);
    }

    public function guardar() {
        $d = json_decode(file_get_contents('php://input'), true) ?? [];
        if (empty($d['id_cliente'])) {
            http_response_code(400);
            echo json_encode(['ok'=>false,'mensaje'=>'Cliente requerido']);
            return;
        }
        $id = $this->modelo->crear($d);
        http_response_code($id ? 201 : 500);
        echo json_encode(['ok'=>(bool)$id,'id_venta'=>$id,'mensaje'=>$id?'Venta registrada':'Error al guardar']);
    }

    public function cancelar() {
        $id = intval($_GET['id'] ?? 0);
        $ok = $this->modelo->cancelar($id);
        echo json_encode(['ok'=>$ok,'mensaje'=>$ok?'Venta cancelada':'Error al cancelar']);
    }
}
