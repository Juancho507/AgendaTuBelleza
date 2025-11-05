<?php
require_once("logica/Persona.php");
require_once("persistencia/ClienteDAO.php");
require_once("persistencia/Conexion.php");

class Cliente extends Persona {
    private $estado;
    private $fechaRegistro;
    
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $contraseña = "", $telefono = "", $estado = 1, $fechaRegistro = "") {
        parent::__construct($id, $nombre, $apellido, $correo, $contraseña, $telefono);
        $this->estado = $estado;
        $this->fechaRegistro = $fechaRegistro;
    }
    
    // ... (Getters y Setters)
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = $fechaRegistro;
    }
    
    
    public function registrar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $claveMd5 = md5($this->contraseña);
        
        // 🟢 CORRECCIÓN 1: Se añade 'id: ""' para corregir la desalineación de argumentos nombrados.
        $clienteDAO = new ClienteDAO(
            id: "",
            nombre: $this->nombre,
            apellido: $this->apellido,
            correo: $this->correo,
            contraseña: $claveMd5, // Clave ya cifrada
            telefono: $this->telefono,
            estado: $this->estado,
            fechaRegistro: date("Y-m-d H:i:s")
            );
        
        $conexion->ejecutar($clienteDAO->registrar());
        $conexion->cerrar();
        return $conexion->getResultado();
    }
    
    
    public function correoExiste() {
        $conexion = new Conexion();
        $conexion->abrir();
        $clienteDAO = new ClienteDAO(correo: $this->correo);
        $conexion->ejecutar($clienteDAO->correoExiste());
        $existe = $conexion->filas() > 0;
        $conexion->cerrar();
        return $existe;
    }
    
    
    public function autenticarse() {
        $conexion = new Conexion();
        $conexion->abrir();
        $claveMd5 = md5($this->contraseña);
        $clienteDAO = new ClienteDAO(correo: $this->correo, contraseña: $claveMd5);
        $conexion->ejecutar($clienteDAO->autenticarse());
        
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
    
    
    public function consultar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $clienteDAO = new ClienteDAO($this->id);
        $conexion->ejecutar($clienteDAO->consultar());
        $datos = $conexion->registro();
        if ($datos) {
            $this->nombre = $datos[0];
            $this->apellido = $datos[1];
            $this->correo = $datos[2];
            $this->telefono = $datos[3];
            $this->estado = $datos[4];
            $this->fechaRegistro = $datos[5];
        }
        $conexion->cerrar();
    }
    
    
    public function actualizar() {
        $conexion = new Conexion();
        $conexion->abrir();
        // Nota: Si la contraseña no se cambia, se puede pasar la que ya está guardada o gestionar el MD5 aquí.
        $clienteDAO = new ClienteDAO($this->id, $this->nombre, $this->apellido, $this->correo, $this->contraseña, $this->telefono, $this->estado);
        $conexion->ejecutar($clienteDAO->actualizar());
        $conexion->cerrar();
    }
    
    
    public function desactivar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $clienteDAO = new ClienteDAO($this->id);
        $conexion->ejecutar($clienteDAO->desactivar());
        $conexion->cerrar();
    }
    
    
    public function activar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $clienteDAO = new ClienteDAO($this->id);
        $conexion->ejecutar($clienteDAO->activar());
        $conexion->cerrar();
    }
}
?>