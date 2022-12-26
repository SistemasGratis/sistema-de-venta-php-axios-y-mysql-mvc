<?php
class Plantilla{
    //pagina principal
    public function index()
    {
        require_once __DIR__ . '../../models/home.php';
        $home = new HomeModel();
        $usuario = $home->getData('usuario');
        $cliente = $home->getData('cliente');
        $producto = $home->getData('producto');
        include_once 'views/principal.php';
    }
    //pagina clientes
    public function clientes()
    {
        include_once 'views/clientes/index.php';
    }
    //pagina usuarios
    public function usuarios()
    {
        include_once 'views/usuarios/index.php';
    }
    //pagina ventas
    public function ventas()
    {
        include_once 'views/ventas/index.php';
    }
    //vista reporte ticket
    public function reporte()
    {
        include_once 'views/ventas/reporte.php';
    }
    //vista reporte ticket
    public function historial()
    {
        include_once 'views/ventas/historial.php';
    }
    //##########productos
    public function productos()
    {
        include_once 'views/productos/index.php';
    }

    public function notFound()
    {
        include_once 'views/errors.php';
    }

}
?>