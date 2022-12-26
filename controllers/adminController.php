<?php
require_once '../models/admin.php';
$option = (empty($_GET['option'])) ? '' : $_GET['option'];
$admin = new AdminModel();
$id_user = $_SESSION['idusuario'];
switch ($option) {
    case 'totales':
        $data['usuario'] = $admin->getDatos('usuario');
        $data['cliente'] = $admin->getDatos('cliente');
        $data['producto'] = $admin->getDatos('producto');
        $data['venta'] = $admin->getVentas($id_user);
        echo json_encode($data);
        break;

    case 'topClientes':
        $data = $admin->topClientes($id_user);
        echo json_encode($data);
        break;

    case 'ventasSemana':
        $actual = date('Y-m-d');
        $fecha = date("Y-m-d", strtotime($actual . '-7 day'));
        $data = $admin->ventasSemana($fecha, $actual, $id_user);
        echo json_encode($data);
        break;

    default:
        # code...
        break;
}
