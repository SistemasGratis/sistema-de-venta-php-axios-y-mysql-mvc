<?php
require_once '../config.php';
require_once 'conexion.php';
class Compras{
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
        $consult = $this->pdo->prepare("INSERT INTO temp_compras (id_usuario, id_producto, cantidad, precio) VALUES (?,?,?,?)");
        return $consult->execute([$id_user, $id_product, $cantidad, $precio]);
    }

    public function getProductsUsers($id_user)
    {
        $consult = $this->pdo->prepare("SELECT temp.*, pro.descripcion FROM temp_compras temp INNER JOIN producto pro ON temp.id_producto = pro.codproducto WHERE temp.id_usuario = ?");
        $consult->execute([$id_user]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCantidad($cantidad, $id)
    {
        $consult = $this->pdo->prepare("UPDATE temp_compras SET cantidad = ? WHERE id = ?");
        return $consult->execute([$cantidad, $id]);
    }

    public function updatePrecio($precio, $id)
    {
        $consult = $this->pdo->prepare("UPDATE temp_compras SET precio = ? WHERE id = ?");
        return $consult->execute([$precio, $id]);
    }

    public function getTemp($id_product, $id_user)
    {
        $consult = $this->pdo->prepare("SELECT * FROM temp_compras WHERE id_producto = ? AND id_usuario = ?");
        $consult->execute([$id_product, $id_user]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function upadteTemp($cantidad, $id_product, $id_user)
    {
        $consult = $this->pdo->prepare("UPDATE temp_compras SET cantidad = ? WHERE id_producto = ? AND id_usuario = ?");
        return $consult->execute([$cantidad, $id_product, $id_user]);
    }

    public function getProveedores()
    {
        $consult = $this->pdo->prepare("SELECT * FROM proveedor WHERE status = 1");
        $consult->execute();
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveCompra($id_proveedor, $total, $fecha, $id_user)
    {
        $consult = $this->pdo->prepare("INSERT INTO compras (id_proveedor, total, fecha, id_usuario) VALUES (?,?,?,?)");
        $consult->execute([$id_proveedor, $total, $fecha, $id_user]);
        return $this->pdo->lastInsertId();
    }

    public function saveDetalle($id_producto, $id_compra, $cantidad, $precio)
    {
        $consult = $this->pdo->prepare("INSERT INTO detalle_compras (id_producto, id_compra, cantidad, precio) VALUES (?,?,?,?)");
        return $consult->execute([$id_producto, $id_compra, $cantidad, $precio]);
    }

    public function deleteTemp($id_user)
    {
        $consult = $this->pdo->prepare("DELETE FROM temp_compras WHERE id_usuario = ?");
        return $consult->execute([$id_user]);
    }

    public function getShoping()
    {
        $consult = $this->pdo->prepare("SELECT c.*, pr.nombre FROM compras c INNER JOIN proveedor pr ON c.id_proveedor = pr.idproveedor");
        $consult->execute();
        return $consult->fetchAll();
    }

    public function getProductsCompra($id_compra)
    {
        $consult = $this->pdo->prepare("SELECT d.*, p.descripcion FROM detalle_compras d INNER JOIN compras c ON d.id_compra = c.id INNER JOIN producto p ON d.id_producto = p.codproducto WHERE c.id = ?");
        $consult->execute([$id_compra]);
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
        $consult = $this->pdo->prepare("DELETE FROM temp_compras WHERE id = ?");
        return $consult->execute([$idTemp]);
    }
    public function updateStock($stock, $id_producto)
    {
        $consult = $this->pdo->prepare("UPDATE producto SET existencia = ? WHERE codproducto = ?");
        return $consult->execute([$stock, $id_producto]);
    }
}

?>