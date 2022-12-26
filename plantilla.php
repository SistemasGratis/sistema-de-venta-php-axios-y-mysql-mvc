<?php
require_once 'config.php';
require_once 'controllers/plantillaController.php';
$plantilla = new Plantilla();
require_once 'views/includes/header.php';
if (isset($_GET['pagina'])) {
    if (empty($_GET['pagina'])) {
        $plantilla->index();
    }else{
        try {
            $archivo = $_GET['pagina'];
            $plantilla->$archivo();
        } catch (\Throwable $th) {
            $plantilla->notFound();
        }
    }
}else{
    $plantilla->index(); 
}
require_once 'views/includes/footer.php';
?>