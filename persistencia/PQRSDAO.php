<?php
class PQRSDAO {
 
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
        $empleadoVal = is_null($this->empleado) ? "NULL" : "'" . $this->empleado . "'";
        
        return "INSERT INTO pqrs (Descripcion, Fecha, TipoPQRS_idTipoPQRS, Cliente_idCliente, Gerente_idGerente, Empleado_idEmpleado)
            VALUES (
                '" . $this->descripcion . "',
                '" . $this->fecha . "',
                '" . $this->tipoPQRS . "',
                '" . $this->cliente . "',
                '" . $this->gerente . "',
                " . $empleadoVal . "  
            )";
    }
  
}
?>