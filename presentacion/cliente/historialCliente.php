<?php
if ($_SESSION["rol"] != "cliente") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

require_once("logica/Cliente.php");

$idCliente = $_SESSION["id"];
$cliente = new Cliente($idCliente);
$historial = $cliente->consultarHistorialCitas();
?>

<?php include("presentacion/encabezadoC.php");  ?>
<?php include("presentacion/menuCliente.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Historial de Citas</h4>
        </div>
        <div class="card-body">
            <?php if (empty($historial)): ?>
                <div class="alert alert-info text-center">
                    Aún no tienes citas registradas en tu historial.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th># Cita</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicio</th>
                                <th>Empleado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $cita): ?>
                                <tr>
                                    <td><?php echo $cita['idCita']; ?></td>
                                    <td><?php echo $cita['Fecha']; ?></td>
                                    <td><?php echo substr($cita['HoraInicio'], 0, 5); ?></td>
                                    <td><?php echo $cita['Servicio']; ?></td>
                                    <td><?php echo $cita['Empleado']; ?></td>
                                    <td><span class="badge bg-<?php echo ($cita['Estado'] == 'Activa') ? 'success' : (($cita['Estado'] == 'Cancelada') ? 'danger' : 'secondary'); ?>"><?php echo $cita['Estado']; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>