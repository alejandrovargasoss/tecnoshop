<?php
class VentaModelo {
    private $bd;
    public function __construct($bd) { $this->bd = $bd; }

    public function obtenerTodos() {
        return $this->bd->query(
            "SELECT v.*,CONCAT(c.nombre,' ',c.apellido) AS cliente
             FROM ventas v
             LEFT JOIN clientes c ON v.id_cliente=c.id
             ORDER BY v.fecha DESC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function crear($d) {
        $stmt = $this->bd->prepare(
            "INSERT INTO ventas (id_cliente,total,estado,observacion)
             VALUES (?,?,?,?)");
        $total  = floatval($d['total'] ?? 0);
        $estado = $d['estado'] ?? 'pendiente';
        $obs    = $d['observacion'] ?? '';
        $stmt->bind_param('idss',$d['id_cliente'],$total,$estado,$obs);
        if ($stmt->execute()) return $this->bd->insert_id;
        return false;
    }

    public function cancelar($id) {
        $stmt = $this->bd->prepare(
            "UPDATE ventas SET estado='cancelada' WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
