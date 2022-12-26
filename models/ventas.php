<?php
require_once '../config.php';
require_once 'conexion.php';
class Ventas{
    private $pdo, $con;
    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }

    public function getProducts()
    {
        $consult = $this->pdo->prepare("SELECT * FROM producto WHERE status = 1");
        $consult->execute();
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduct($cve)
    {
        $consult = $this->pdo->prepare("SELECT * FROM producto WHERE codproducto = ?");
        $consult->execute([$cve]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function addTemp($id_user, $id_product, $cantidad, $precio)
    {
        $consult = $this->pdo->prepare("INSERT INTO detalle_temp (id_usuario, id_producto, cantidad, precio) VALUES (?,?,?,?)");
        return $consult->execute([$id_user, $id_product, $cantidad, $precio]);
    }

    public function getProductsUsers($id_user)
    {
        $consult = $this->pdo->prepare("SELECT temp.*, pro.descripcion FROM detalle_temp temp INNER JOIN producto pro ON temp.id_producto = pro.codproducto WHERE temp.id_usuario = ?");
        $consult->execute([$id_user]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCantidad($cantidad, $id)
    {
        $consult = $this->pdo->prepare("UPDATE detalle_temp SET cantidad = ? WHERE id = ?");
        return $consult->execute([$cantidad, $id]);
    }

    public function updatePrecio($precio, $id)
    {
        $consult = $this->pdo->prepare("UPDATE detalle_temp SET precio = ? WHERE id = ?");
        return $consult->execute([$precio, $id]);
    }

    public function getTemp($id_product, $id_user)
    {
        $consult = $this->pdo->prepare("SELECT * FROM detalle_temp WHERE id_producto = ? AND id_usuario = ?");
        $consult->execute([$id_product, $id_user]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function upadteTemp($cantidad, $id_product, $id_user)
    {
        $consult = $this->pdo->prepare("UPDATE detalle_temp SET cantidad = ? WHERE id_producto = ? AND id_usuario = ?");
        return $consult->execute([$cantidad, $id_product, $id_user]);
    }

    public function getClients()
    {
        $consult = $this->pdo->prepare("SELECT * FROM cliente");
        $consult->execute();
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveVenta($id_cliente, $total, $metodo, $id_user)
    {
        $consult = $this->pdo->prepare("INSERT INTO ventas (id_cliente, total, metodo, id_usuario) VALUES (?,?,?,?)");
        $consult->execute([$id_cliente, $total, $metodo, $id_user]);
        return $this->pdo->lastInsertId();
    }

    public function saveDetalle($id_producto, $id_venta, $cantidad, $precio)
    {
        $consult = $this->pdo->prepare("INSERT INTO detalle_venta (id_producto, id_venta, cantidad, precio) VALUES (?,?,?,?)");
        return $consult->execute([$id_producto, $id_venta, $cantidad, $precio]);
    }

    public function deleteTemp($id_user)
    {
        $consult = $this->pdo->prepare("DELETE FROM detalle_temp WHERE id_usuario = ?");
        return $consult->execute([$id_user]);
    }

    public function getSales()
    {
        $consult = $this->pdo->prepare("SELECT v.*, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente");
        $consult->execute();
        return $consult->fetchAll();
    }

    public function getProductsVenta($id_venta)
    {
        $consult = $this->pdo->prepare("SELECT d.*, p.descripcion FROM detalle_venta d INNER JOIN ventas v ON d.id_venta = v.id INNER JOIN producto p ON d.id_producto = p.codproducto WHERE v.id = ?");
        $consult->execute([$id_venta]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBarcode($barcode)
    {
        $consult = $this->pdo->prepare("SELECT * FROM producto WHERE codigo = ?");
        $consult->execute([$barcode]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteProducto($idTemp)
    {
        $consult = $this->pdo->prepare("DELETE FROM detalle_temp WHERE id = ?");
        return $consult->execute([$idTemp]);
    }
    public function updateStock($stock, $id_producto)
    {
        $consult = $this->pdo->prepare("UPDATE producto SET existencia = ? WHERE codproducto = ?");
        return $consult->execute([$stock, $id_producto]);
    }
}

?>