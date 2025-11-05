<?php
session_start();
require("logica/Cliente.php");
require("logica/Gerente.php");
require("logica/Empleado.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agenda tu belleza</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
</head>

<body class="bg-light">

<?php

$paginas_sin_autenticacion = array(
    "presentacion/autenticarse.php",
    "presentacion/cliente/registroCliente.php",
);


$paginas_con_autenticacion = array(
    "presentacion/cliente/editarCliente.php",
    "presentacion/cliente/eliminarCliente.php",
    "presentacion/sesionCliente.php",
    "presentacion/sesionEmpleado.php",
    "presentacion/sesionGerente.php"
);


if (!isset($_GET["pid"])) {
    include("presentacion/autenticarse.php");
} else {
    $pid = base64_decode($_GET["pid"]);

    if (in_array($pid, $paginas_sin_autenticacion)) {
        include $pid;
    } else if (in_array($pid, $paginas_con_autenticacion)) {
        if (!isset($_SESSION["id"])) {
            include("presentacion/autenticarse.php");
        } else {
            include $pid;
        }
    } else {
        echo "<div class='container mt-5'><h3 class='text-danger text-center'>Error 404 - Página no encontrada</h3></div>";
    }
}
?>

<footer class="text-center py-3 mt-5 bg-white border-top shadow-sm">
  <small class="text-muted">
    &copy; <?php echo date("Y"); ?> Peluquería AgendaTuBelleza. Todos los derechos reservados.
  </small>
</footer>

</body>
</html>
