<?php
require_once 'conexion.php';
class Reporte
{
    private $pdo, $con;
    public function __construct()
    {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }
    public function getConfiguracion()
    {
        $consult = $this->pdo->prepare("SELECT * FROM configuracion");
        $consult->execute();
        return $consult->fetch(PDO::FETCH_ASSOC);
    }
    public function getSale($id_venta)
    {
        $consult = $this->pdo->prepare("SELECT v.*, c.* FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente WHERE v.id = ?");
        $consult->execute([$id_venta]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }
    public function getProducts($id_venta)
    {
        $consult = $this->pdo->prepare("SELECT d.*, p.descripcion FROM detalle_venta d INNER JOIN ventas v ON d.id_venta = v.id INNER JOIN producto p ON d.id_producto = p.codproducto WHERE v.id = ?");
        $consult->execute([$id_venta]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

}
