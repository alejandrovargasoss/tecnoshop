<?php
class ClienteModelo {
    private $bd;
    public function __construct($bd) { $this->bd = $bd; }

    public function obtenerTodos() {
        return $this->bd->query(
            "SELECT * FROM clientes WHERE estado='activo' ORDER BY nombre ASC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->bd->prepare(
            "SELECT * FROM clientes WHERE id=? AND estado='activo'");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function crear($d) {
        $stmt = $this->bd->prepare(
            "INSERT INTO clientes
             (nombre,apellido,documento,telefono,email,direccion,estado)
             VALUES (?,?,?,?,?,?,'activo')");
        $stmt->bind_param('ssssss',
            $d['nombre'],   $d['apellido'],
            $d['documento'],$d['telefono'],
            $d['email'],    $d['direccion']);
        return $stmt->execute();
    }

    public function actualizar($d) {
        $stmt = $this->bd->prepare(
            "UPDATE clientes
             SET nombre=?,apellido=?,telefono=?,email=?,direccion=?,estado=?
             WHERE id=?");
        $stmt->bind_param('ssssssi',
            $d['nombre'],   $d['apellido'],
            $d['telefono'], $d['email'],
            $d['direccion'],$d['estado'],$d['id']);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->bd->prepare(
            "UPDATE clientes SET estado='inactivo' WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
