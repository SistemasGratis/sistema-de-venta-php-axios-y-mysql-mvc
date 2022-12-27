<?php
require_once 'config.php';
require_once 'conexion.php';
class PermisosModel{
    private $pdo, $con;
    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }

    public function getPermiso($permiso, $id_usuario)
    {
        $consult = $this->pdo->prepare("SELECT id_permiso FROM detalle_permisos WHERE id_permiso = ? AND id_usuario = ?");
        $consult->execute([$permiso, $id_usuario]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }
}

?>