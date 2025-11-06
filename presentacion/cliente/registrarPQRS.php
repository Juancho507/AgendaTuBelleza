<?php
if ($_SESSION["rol"] != "cliente") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

require_once("logica/PQRS.php");
require_once("logica/Empleado.php");

$idCliente = $_SESSION["id"];
$exito = false;
$error = false;

$tiposPQRS = PQRS::consultarTiposPQRS(); 
$empleados = Empleado::consultarTodos();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['registrarPQRS'])) {
    
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipoPQRS'];
    $empleadoId = empty($_POST['empleado']) ? NULL : $_POST['empleado']; 

    $pqrs = new PQRS(
        "",
        descripcion: $descripcion,
        fecha: "",
        tipoPQRS: $tipo,
        cliente: $idCliente,
        gerente: 1, 
        empleado: $empleadoId
    );
    
    try {
        $pqrs->registrar();
        $exito = true;
    } catch (Exception $e) {
        $error = true;
    }
}
?>

<?php include("presentacion/encabezadoC.php"); ?>
<?php include("presentacion/menuCliente.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Registrar Petición, Queja, Reclamo o Sugerencia (PQRS)</h4>
        </div>
        <div class="card-body">
            
            <?php if ($exito): ?>
                <div class="alert alert-success text-center">✅ PQRS registrado con éxito. Pronto te contactaremos.</div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger text-center">❌ Error al registrar PQRS. Inténtalo de nuevo.</div>
            <?php endif; ?>

            <form method="POST">
                
                <div class="mb-3">
                    <label for="tipoPQRS" class="form-label">Tipo de Solicitud</label>
                    <select class="form-select" id="tipoPQRS" name="tipoPQRS" required>
                        <option value="">Selecciona el tipo...</option>
                        <?php foreach ($tiposPQRS as $id => $nombre): ?>
                            <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="empleado" class="form-label">Empleado Relacionado (Opcional)</label>
                    <select class="form-select" id="empleado" name="empleado">
                        <option value="">--- Ningún Empleado (General) ---</option>
                        <?php foreach ($empleados as $e): ?>
                            <option value="<?php echo $e['idEmpleado']; ?>"><?php echo $e['NombreCompleto']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Detalle (Descripción)</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                </div>
                
                <p class="text-muted small">Tu solicitud será asignada automáticamente al Gerente Principal para su seguimiento.</p>

                <button type="submit" name="registrarPQRS" class="btn btn-danger w-100 mt-3">Enviar PQRS</button>
            </form>
        </div>
    </div>
</div>