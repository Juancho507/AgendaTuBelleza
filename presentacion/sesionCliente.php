<?php
if ($_SESSION["rol"] != "cliente") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
$id = $_SESSION["id"];
?>
<body>
<?php 
include("presentacion/encabezadoC.php");
include("presentacion/menuCliente.php");

$cliente = new Cliente($id);
$cliente->consultar();
?>
<div class="container mt-5">
  <div class="row">
    <div class="col-md-7 mx-auto"> 
      <div class="card">
        <div class="card-body">
          <h2 class="my-2 text-center">Perfil del Cliente</h2>
          
          <div class="table-responsive-sm my-4">
            <table class="table table-striped table-hover">
              <tr>
                <th>ID</th>
                <td><?php echo $cliente->getId(); ?></td>
              </tr>
              <tr>
                <th>Nombre</th>
                <td><?php echo $cliente->getNombre(); ?></td>
              </tr>
              <tr>
                <th>Apellido</th>
                <td><?php echo $cliente->getApellido(); ?></td>
              </tr>
              <tr>
                <th>Correo</th>
                <td><?php echo $cliente->getCorreo(); ?></td>
              </tr>
              <tr>
                <th>Contacto</th>
                <td><?php echo $cliente->getTelefono(); ?></td>
              </tr>
            </table>
          </div>
          
          <div class="text-center mt-3">
              <a href="?pid=<?php echo base64_encode('presentacion/cliente/editarCliente.php'); ?>" class="btn btn-primary">
                  Editar Perfil
              </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
</body>