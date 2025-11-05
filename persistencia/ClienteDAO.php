<?php
class ClienteDAO {
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $contraseña;
    private $telefono;
    private $estado;
    private $fechaRegistro;
    
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $contraseña = "", $telefono = "", $estado = "", $fechaRegistro = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->contraseña = $contraseña;
        $this->telefono = $telefono;
        $this->estado = $estado;
        $this->fechaRegistro = $fechaRegistro;
    }
    
    public function registrar() {
        // 🟢 CORRECCIÓN 2: Se añade el Gerente_idGerente con un valor predeterminado (debe existir en la tabla 'gerente')
        $gerenteIdDefecto = 1;
        
        return "INSERT INTO Cliente (Nombre, Apellido, Correo, Contraseña, Telefono, Estado, FechaRegistro, Gerente_idGerente)
                VALUES (
                    '" . $this->nombre . "',
                    '" . $this->apellido . "',
                    '" . $this->correo . "',
                    '" . $this->contraseña . "',
                    '" . $this->telefono . "',
                    " . $this->estado . ",
                    '" . $this->fechaRegistro . "',
                    " . $gerenteIdDefecto . "
                )";
    }
    
    public function correoExiste() {
        return "SELECT idCliente FROM Cliente WHERE Correo = '{$this->correo}'";
    }
    
    public function autenticarse() {
        return "SELECT idCliente
                FROM Cliente
                WHERE Correo = '" . $this->correo . "'
                AND Contraseña = '" . $this->contraseña . "'";
    }
    
    public function consultar() {
        return "SELECT Nombre, Apellido, Correo, Telefono, Estado, FechaRegistro
                FROM Cliente
                WHERE idCliente = " . $this->id;
    }
    
    public function actualizar() {
        return "UPDATE Cliente SET
                    Nombre = '" . $this->nombre . "',
                    Apellido = '" . $this->apellido . "',
                    Correo = '" . $this->correo . "',
                    Contraseña = '" . $this->contraseña . "',
                    Telefono = '" . $this->telefono . "',
                    Estado = " . $this->estado . "
                WHERE idCliente = " . $this->id;
    }
    
    public function desactivar() {
        return "UPDATE Cliente SET Estado = 0 WHERE idCliente = '{$this->id}'";
    }
    
    public function activar() {
        return "UPDATE Cliente SET Estado = 1 WHERE idCliente = '{$this->id}'";
    }
}
?>