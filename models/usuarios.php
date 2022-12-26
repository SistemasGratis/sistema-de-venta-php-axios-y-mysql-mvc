<?php
require_once '../config.php';
require_once 'conexion.php';
class UsuariosModel{
    private $pdo, $con;
    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }

    public function getLogin($email)
    {
        $consult = $this->pdo->prepare("SELECT * FROM usuario WHERE correo = ?");
        $consult->execute([$email]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsers()
    {
        $consult = $this->pdo->prepare("SELECT * FROM usuario WHERE status = 1");
        $consult->execute();
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser($id)
    {
        $consult = $this->pdo->prepare("SELECT * FROM usuario WHERE idusuario = ?");
        $consult->execute([$id]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function comprobarCorreo($correo)
    {
        $consult = $this->pdo->prepare("SELECT * FROM usuario WHERE correo = ?");
        $consult->execute([$correo]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function saveUser($nombre, $correo, $clave)
    {
        $consult = $this->pdo->prepare("INSERT INTO usuario (nombre, correo, clave) VALUES (?,?,?)");
        return $consult->execute([$nombre, $correo, $clave]);
    }

    public function deleteUser($id)
    {
        $consult = $this->pdo->prepare("UPDATE usuario SET status = ? WHERE idusuario = ?");
        return $consult->execute([0, $id]);
    }

    public function updateUser($nombre, $correo, $id)
    {
        $consult = $this->pdo->prepare("UPDATE usuario SET nombre=?, correo=? WHERE idusuario=?");
        return $consult->execute([$nombre, $correo, $id]);
    }

    public function getPermisos()
    {
        $consult = $this->pdo->prepare("SELECT * FROM permisos");
        $consult->execute();
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDetalle($id_user)
    {
        $consult = $this->pdo->prepare("SELECT * FROM detalle_permisos WHERE id_usuario = ?");
        $consult->execute([$id_user]);
        return $consult->fetchAll(PDO::FETCH_ASSOC);
    }

    public function savePermiso($permiso, $id_user)
    {
        $consult = $this->pdo->prepare("INSERT INTO detalle_permisos (id_permiso, id_usuario) VALUES (?,?)");
        return $consult->execute([$permiso, $id_user]);
    }

    public function eliminarPermisos($id_user)
    {
        $consult = $this->pdo->prepare("DELETE FROM detalle_permisos WHERE id_usuario = ?");
        return $consult->execute([$id_user]);
    }
}

?>