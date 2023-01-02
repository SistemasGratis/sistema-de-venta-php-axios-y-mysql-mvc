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

    public function getProductsVenta($id_venta)
    {
        $consult = $this->pdo->prepare("SELECT d.*, p.descripcion FROM detalle_ventas d INNER JOIN ventas v ON d.id_venta = v.id INNER JOIN producto p ON d.id_producto = p.codproducto WHERE v.id = ?");
        $consult->execute([$id_venta]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsCompra($id_compra)
    {
        $consult = $this->pdo->prepare("SELECT d.*, p.descripcion FROM detalle_compras d INNER JOIN compras c ON d.id_compra = c.id INNER JOIN producto p ON d.id_producto = p.codproducto WHERE c.id = ?");
        $consult->execute([$id_compra]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getShoping($id_compra)
    {
        $consult = $this->pdo->prepare("SELECT c.*, p.* FROM compras c INNER JOIN proveedor p ON c.id_proveedor = p.idproveedor WHERE c.id = ?");
        $consult->execute([$id_compra]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

}
