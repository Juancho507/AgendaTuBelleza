<?php
require_once("persistencia/PQRSDAO.php");
require_once("persistencia/Conexion.php");

class PQRS {
    private $id;
    private $descripcion;
    private $fecha;
    private $tipoPQRS;
    private $cliente;
    private $gerente;
    private $empleado;
    
    public function __construct(
        $id = "",
        $descripcion = "",
        $fecha = "",
        $tipoPQRS = "",
        $cliente = "",
        $gerente = "",
        $empleado = ""
        ) {
            $this->id = $id;
            $this->descripcion = $descripcion;
            $this->fecha = $fecha;
            $this->tipoPQRS = $tipoPQRS;
            $this->cliente = $cliente;
            $this->gerente = $gerente;
            $this->empleado = $empleado;
    }
    
    
    public function registrar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $gerenteId = $this->gerente ?: 1;
        $pqrsDAO = new PQRSDAO(
            "",                               
            $this->descripcion,              
            date("Y-m-d H:i:s"),            
            $this->tipoPQRS,              
            $this->cliente,                
            $gerenteId,                     
            $this->empleado                  
            );
        
        $conexion->ejecutar($pqrsDAO->registrar());
        $conexion->cerrar();
        return $conexion->getResultado();
    }
    
    public static function consultarTiposPQRS() {
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar("SELECT idTipoPQRS, Tipo FROM tipopqrs");
        $tipos = [];
        while ($registro = $conexion->registro()) {
            $tipos[$registro[0]] = $registro[1];
        }
        $conexion->cerrar();
        return $tipos;
    }
}
?>