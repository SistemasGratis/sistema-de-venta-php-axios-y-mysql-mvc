<?php
require_once '../config.php';
require_once 'conexion.php';
class AdminModel{
    private $pdo, $con;
    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }

    public function getDatos($table)
    {
        $consult = $this->pdo->prepare("SELECT COUNT(*) AS total FROM $table WHERE status = ?");
        $consult->execute([1]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function getVentas($id_user)
    {
        $consult = $this->pdo->prepare("SELECT COUNT(*) AS total FROM ventas WHERE id_usuario = ?");
        $consult->execute([$id_user]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function topClientes($id_user)
    {
        $consult = $this->pdo->prepare("SELECT SUM(v.total) AS total, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente WHERE id_usuario = ? GROUP BY v.id_cliente LIMIT 5");
        $consult->execute([$id_user]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ventasSemana($fecha, $actual, $id_user)
    {
        $consult = $this->pdo->prepare("SELECT SUM(total) AS total, fecha FROM ventas WHERE fecha BETWEEN ? AND ? AND id_usuario = ? GROUP BY fecha");
        $consult->execute([$fecha, $actual, $id_user]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDato()
    {
        $consult = $this->pdo->prepare("SELECT * FROM configuracion");
        $consult->execute();
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function saveDatos($nombre, $telefono, $correo, $direccion, $id)
    {
        $consult = $this->pdo->prepare("UPDATE configuracion SET nombre=?, telefono=?, email=?, direccion=? WHERE id = ?");
        return $consult->execute([$nombre, $telefono, $correo, $direccion, $id]);
    }
}

?>