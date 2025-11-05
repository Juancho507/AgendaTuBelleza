<?php
if ($_SESSION["rol"] != "cliente") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$error = 0;
$id = $_SESSION["id"];
$cliente = new Cliente($id);
$cliente->consultar();

if (isset($_POST["editar"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $claveNueva = $_POST["contraseña"];
    $telefono = $_POST["telefono"];
    $estado = $_POST["estado"];

    // Si no se cambia la contraseña, conservar la anterior
    $claveFinal = $cliente->getContraseña();
    if (!empty($claveNueva)) {
        $claveFinal = md5($claveNueva);
    }

    // Crear objeto actualizado y guardar
    try {
        $clienteActualizado = new Cliente(
            $id,
            $nombre,
            $apellido,
            $correo,
            $claveFinal,
            $telefono,
            $estado,
            $cliente->getFechaRegistro()
        );
        $clienteActualizado->actualizar();
        $cliente = $clienteActualizado;
    } catch (Exception $e) {
        $error = 1;
    }
}
?>
<body>
<?php
include("presentacion/encabezadoC.php");
include("presentacion/menuCliente.php");
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4"> 
                <div class="card-header bg-primary text-white">
                    <h4>Editar Perfil</h4>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_POST["editar"]) && $error == 0) {
                        echo "<div class='alert alert-success'>Datos actualizados correctamente.</div>";
                    } elseif (isset($_POST["editar"]) && $error == 1) {
                        echo "<div class='alert alert-danger'>Ocurrió un error al actualizar la información.</div>";
                    }
                    ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($cliente->getNombre()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($cliente->getApellido()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($cliente->getCorreo()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña (dejar en blanco si no deseas cambiarla)</label>
                            <input type="password" name="contraseña" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($cliente->getTelefono()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="1" <?php echo $cliente->getEstado() == 1 ? 'selected' : ''; ?>>Activo</option>
                                <option value="0" <?php echo $cliente->getEstado() == 0 ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                    </form>

                    <hr>
                    <form method="post" action="?pid=<?php echo base64_encode("presentacion/cliente/eliminarCliente.php"); ?>" onsubmit="return confirmarEliminacion();">
                        <button type="submit" name="eliminar" class="btn btn-danger">Eliminar Cuenta</button>
                    </form>
                    <script>
                        function confirmarEliminacion() {
                            return confirm("¿Estás seguro de eliminar tu cuenta? Esta acción no se puede deshacer.");
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
