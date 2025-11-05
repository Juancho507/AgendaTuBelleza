<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "cliente") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$id = $_SESSION["id"];
$cliente = new Cliente($id);
$cliente->consultar();

$clienteInactivo = new Cliente(
    $cliente->getId(),
    $cliente->getNombre(),
    $cliente->getApellido(),
    $cliente->getCorreo(),
    $cliente->getContraseña(),
    $cliente->getTelefono(),
    0, // Estado = inactivo
    $cliente->getFechaRegistro()
);

$clienteInactivo->actualizar();


session_destroy();

header("Location: ?pid=" . base64_encode("presentacion/autenticarse.php") . "&desactivado=1");
exit();
?>
