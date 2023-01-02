<?php
$option = (empty($_GET['option'])) ? '' : $_GET['option'];
require_once '../models/compras.php';
$compras = new Compras();
$id_user = $_SESSION['idusuario'];
switch ($option) {
    case 'listar':
        $result = $compras->getProducts();
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]['addcart'] = '<a href="#" class="btn btn-primary btn-sm" onclick="addCart(' . $result[$i]['codproducto'] . ')"><i class="fas fa-cart-plus"></i></a>';
            if ($result[$i]['existencia'] > 15) {
                $result[$i]['cantidad'] = '<span class="badge badge-info">'.$result[$i]['existencia'].'</span>';
            } else {
                $result[$i]['cantidad'] = '<span class="badge badge-warning">'.$result[$i]['existencia'].'</span>';
            }           
            
        }
        echo json_encode($result);
        break;
    case 'addcart':
        $cve = $_GET['id'];
        $result = $compras->getProduct($cve);
        $id_product = $result['codproducto'];
        $cantidad = 1;
        $precio = $result['precio'];
        $consult = $compras->getTemp($id_product, $id_user);

        if (empty($consult)) {
            $temp = $compras->addTemp($id_user, $id_product, $cantidad, $precio);
            if ($temp) {
                $res = array('tipo' => 'success', 'mensaje' => 'PRODUCTO AGREGADO AL CARRITO');
            } else {
                $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL AGREGAR');
            }
        } else {
            $cantidad = $consult['cantidad'] + 1;
            $temp = $compras->upadteTemp($cantidad, $id_product, $id_user);
            if ($temp) {
                $res = array('tipo' => 'success', 'mensaje' => 'PRODUCTO INCREMENTADO');
            } else {
                $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL AGREGAR');
            }
        }

        echo json_encode($res);
        break;
    case 'listarTemp':
        $result = $compras->getProductsUsers($id_user);
        echo json_encode($result);
        break;
    case 'addcantidad':
        $accion = file_get_contents('php://input');
        $array = json_decode($accion, true);
        $idTemp = $array['id'];
        $cantidad = $array['cantidad'];
        $result = $compras->updateCantidad($cantidad, $idTemp);
        if ($result) {
            $res = array('tipo' => 'success', 'mensaje' => 'ok');
        } else {
            $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL AGREGAR');
        }
        echo json_encode($res);
        break;
    case 'addprecio':
        $accion = file_get_contents('php://input');
        $array = json_decode($accion, true);
        $idTemp = $array['id'];
        $precio = $array['precio'];
        $result = $compras->updatePrecio($precio, $idTemp);
        if ($result) {
            $res = array('tipo' => 'success', 'mensaje' => 'ok');
        } else {
            $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL AGREGAR');
        }
        echo json_encode($res);
        break;
    case 'listar-proveedores':
        $result = $compras->getProveedores();
        echo json_encode($result);
        break;
    case 'savecompra':
        $accion = file_get_contents('php://input');
        $array = json_decode($accion, true);
        $id_proveedor = $array['idProveedor'];
        $fecha = date('Y-m-d');
        $consult = $compras->getProductsUsers($id_user);
        if (empty($consult)) {
            $res = array('tipo' => 'error', 'mensaje' => 'CARRITO VACIO');
        } else {
            $total = 0.00;
            foreach ($consult as $temp) {
                $total += $temp['cantidad'] * $temp['precio'];
            }
            $shoping = $compras->saveCompra($id_proveedor, $total, $fecha, $id_user);
            if ($shoping > 0) {
                foreach ($consult as $temp) {
                    $compras->saveDetalle($temp['id_producto'], $shoping, $temp['cantidad'], $temp['precio']);
                    $producto = $compras->getProduct($temp['id_producto']);
                    $stock = $producto['existencia'] + $temp['cantidad'];
                    $compras->updateStock($stock, $temp['id_producto']);
                }
                $compras->deleteTemp($id_user);
                $res = array('tipo' => 'success', 'mensaje' => 'ok', 'shoping' => $shoping);
            } else {
                $res = array('tipo' => 'success', 'mensaje' => 'error');
            }
        }
        echo json_encode($res);
        break;
    case 'historial':
        $historial = $compras->getShoping();
        for ($i = 0; $i < count($historial); $i++) {
            $historial[$i]['producto'] = '';
            $productos = $compras->getProductsCompra($historial[$i]['id']);
            foreach ($productos as $producto) {
                $historial[$i]['producto'] .= '<li>' . $producto['descripcion'] . '</li>';
            }
            $historial[$i]['accion'] = '<a href="?pagina=reporte_compra&shoping=' . $historial[$i]['id'] . '">PDF</a>';
        }
        echo json_encode($historial);
        break;
    case 'searchbarcode':
        $barcode = $_GET['barcode'];
        $producto = $compras->getBarcode($barcode);
        if (empty($producto)) {
            $res = array('tipo' => 'error', 'mensaje' => 'PRODUCTO NO EXISTE');
        } else {
            $consult = $compras->getTemp($producto['codproducto'], $id_user);
            if (empty($consult)) {
                $cantidad = 1;
                $temp = $compras->addTemp($id_user, $producto['codproducto'], $cantidad, $producto['precio']);
                if ($temp) {
                    $res = array('tipo' => 'success', 'mensaje' => 'PRODUCTO AGREGADO AL CARRITO');
                } else {
                    $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL AGREGAR');
                }
            } else {
                $cantidad = $consult['cantidad'] + 1;
                $temp = $compras->upadteTemp($cantidad, $producto['codproducto'], $id_user);
                if ($temp) {
                    $res = array('tipo' => 'success', 'mensaje' => 'PRODUCTO INCREMENTADO');
                } else {
                    $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL AGREGAR');
                }
            }
        }

        echo json_encode($res);
        break;
    case 'delete':
        $id = $_GET['id'];
        $temp = $compras->deleteProducto($id);
        if ($temp) {
            $res = array('tipo' => 'success', 'mensaje' => 'PRODUCTO ELIMINADO');
        } else {
            $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL ELIMINAR');
        }
        echo json_encode($res);
        break;

    default:
        # code...
        break;
}