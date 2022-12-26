<?php
require_once 'config.php';
require_once 'conexion.php';
class HomeModel{
    private $pdo, $con;
    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }

    public function getData($table)
    {
        $consult = $this->pdo->prepare("SELECT COUNT(*) AS total FROM $table WHERE status = 1");
        $consult->execute();
        return $consult->fetch(PDO::FETCH_ASSOC);
    }
}

?>