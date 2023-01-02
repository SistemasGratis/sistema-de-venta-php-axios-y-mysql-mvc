<?php
require_once '../models/proveedor.php';
$option = (empty($_GET['option'])) ? '' : $_GET['option'];
$proveedor = new ProveedorModel();
switch ($option) {
    case 'listar':
        $data = $proveedor->getProveedores();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['accion'] = '<div class="d-flex">
                <a class="btn btn-danger btn-sm" onclick="deleteProveedor(' . $data[$i]['idproveedor'] . ')"><i class="fas fa-eraser"></i></a>
                <a class="btn btn-primary btn-sm" onclick="editProveedor(' . $data[$i]['idproveedor'] . ')"><i class="fas fa-edit"></i></a>
                </div>';
        }
        echo json_encode($data);
        break;
    case 'save':
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $id_proveedor = $_POST['id_proveedor'];
        if ($id_proveedor == '') {
            $consult = $proveedor->comprobarTelefono($telefono);
            if (empty($consult)) {
                $result = $proveedor->saveProveedor($nombre, $telefono, $direccion);
                if ($result) {
                    $res = array('tipo' => 'success', 'mensaje' => 'PROVEEDOR REGISTRADO');
                } else {
                    $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL AGREGAR');
                }
            } else {
                $res = array('tipo' => 'error', 'mensaje' => 'EL TELEFONO YA EXISTE');
            }
        } else {
            $result = $proveedor->updateProveedor($nombre, $telefono, $direccion, $id_proveedor);
            if ($result) {
                $res = array('tipo' => 'success', 'mensaje' => 'PROVEEDOR MODIFICADO');
            } else {
                $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL MODIFICAR');
            }
        }
        echo json_encode($res);
        break;
    case 'delete':
        $id = $_GET['id'];
        $data = $proveedor->deleteProveedor($id);
        if ($data) {
            $res = array('tipo' => 'success', 'mensaje' => 'PROVEEDOR ELIMINADO');
        } else {
            $res = array('tipo' => 'error', 'mensaje' => 'ERROR AL ELIMINAR');
        }
        echo json_encode($res);
        break;
    case 'edit':
        $id = $_GET['id'];
        $data = $proveedor->getProveedor($id);
        echo json_encode($data);
        break;

    default:
        # code...
        break;
}