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
    $foto = $_FILES["foto"]["name"];
    $tam = $_FILES["foto"]["size"];
    $rutaLocal = $_FILES["foto"]["tmp_name"];
    
    $claveFinal = $cliente->getContraseña();
    if (!empty($claveNueva)) {
        $claveFinal = md5($claveNueva);
    }
    
    // --- Manejo de la foto ---
    $rutaServidor = $cliente->getFoto();
    if ($foto != "") {
        $nuevoNombre = time() . ".png";
        $rutaServidor = "imagenes/" . $nuevoNombre;
        if (copy($rutaLocal, $rutaServidor)) {
            // Eliminar foto anterior si existe
            if ($cliente->getFoto() != "") {
                $rutaFotoAnterior = __DIR__ . "/../../" . $cliente->getFoto();
                if (file_exists($rutaFotoAnterior)) {
                    unlink($rutaFotoAnterior);
                }
            }
        } else {
            $error = 1; // error al subir foto
        }
    }
    
    if ($error == 0) {
        try {
            $clienteActualizado = new Cliente(
                $id,
                $nombre,
                $apellido,
                $correo,
                $claveFinal,
                $telefono,
                $cliente->getEstado(),
                $cliente->getFechaRegistro(),
                $cliente->getGerente(),
                $rutaServidor
                );
            $clienteActualizado->actualizar();
            $cliente = $clienteActualizado;
        } catch (Exception $e) {
            $error = 1;
        }
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
                        echo "<div class='alert alert-danger'>Ocurrió un error al actualizar la información o la foto.</div>";
                    }
                    ?>
                    <form method="post" enctype="multipart/form-data">
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
    							<label class="form-label">Estado de la Cuenta</label>
   								<input type="text" class="form-control" value="<?php echo $cliente->getEstado() == 1 ? 'Activo' : 'Inactivo (Acción administrativa)'; ?>" disabled>
    							<input type="hidden" name="estado" value="<?php echo $cliente->getEstado(); ?>"> 
							</div>

                        <!-- FOTO ACTUAL -->
                        <div class="mb-3 text-center">
                            <?php
                            if ($cliente->getFoto() != "" && file_exists($cliente->getFoto())) {
                                echo "<img src='" . $cliente->getFoto() . "' height='150' class='rounded-circle mb-2' />";
                            } else {
                                echo "<p>No hay foto actual.</p>";
                            }
                            ?>
                        </div>

                        <!-- NUEVA FOTO -->
                        <div class="mb-3">
                            <label class="form-label">Foto Nueva</label>
                            <input type="file" name="foto" class="form-control">
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
