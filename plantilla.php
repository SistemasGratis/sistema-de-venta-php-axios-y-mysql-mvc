<?php
require_once 'config.php';
require_once 'controllers/plantillaController.php';
$plantilla = new Plantilla();
##### PERMISOS #####

require_once 'models/permisos.php';
$id_user = $_SESSION['idusuario'];
$permisos = new PermisosModel();
$usuarios = $permisos->getPermiso(2, $id_user);
$clientes = $permisos->getPermiso(3, $id_user);
$productos = $permisos->getPermiso(4, $id_user);
$ventas = $permisos->getPermiso(5, $id_user);
$nueva_venta = $permisos->getPermiso(6, $id_user);

##### FIN PERMISOS ####
require_once 'views/includes/header.php';
if (isset($_GET['pagina'])) {
    if (empty($_GET['pagina'])) {
        $plantilla->index();
    }else{
        try {
            $archivo = $_GET['pagina'];
            if ($archivo == 'usuarios' && !empty($usuarios)) {
                $plantilla->$archivo();
            } else if ($archivo == 'clientes' && !empty($clientes)) {
                $plantilla->$archivo();
            } else if ($archivo == 'productos' && !empty($productos)) {
                $plantilla->$archivo();
            } else if ($archivo == 'ventas' && !empty($nueva_venta)) {
                $plantilla->$archivo();
            } else if ($archivo == 'historial' && !empty($ventas)) {
                $plantilla->$archivo();
            } else{
                $plantilla->notFound();
            }          
        } catch (\Throwable $th) {
            $plantilla->notFound();
        }
    }
}else{
    $plantilla->index(); 
}
require_once 'views/includes/footer.php';
?>