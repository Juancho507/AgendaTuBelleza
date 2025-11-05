<?php
class GerenteDAO {
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $contraseña;
    
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $contraseña = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->contraseña = $contraseña;
    }
    
    public function autenticarse() {
        return "SELECT idGerente FROM gerente WHERE correo = '{$this->correo}' AND contraseña = '{$this->contraseña}'";
    }
}
?>
