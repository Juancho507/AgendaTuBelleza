<?php
require_once("logica/Persona.php");
require_once("persistencia/GerenteDAO.php");
require_once("persistencia/Conexion.php");

class Gerente extends Persona {

    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $contraseña = "", $telefono = "") {
        parent::__construct($id, $nombre, $apellido, $correo, $contraseña, $telefono);
    }

    public function autenticarse() {
        $conexion = new Conexion();
        $conexion->abrir();

        $claveMd5 = md5($this->contraseña);
        $gerenteDAO = new GerenteDAO("", "", $this->correo, $claveMd5);
        $conexion->ejecutar($gerenteDAO->autenticarse());

        if ($conexion->filas() == 1) {
            $datos = $conexion->registro();
            $this->id = $datos[0];
            $conexion->cerrar();
            return true;
        } else {
            $conexion->cerrar();
            return false;
        }
    }
}
?>
