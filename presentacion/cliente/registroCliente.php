<?php
$exito = false;
$error = false;
$correoDuplicado = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Obtener todos los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];
    $telefono = $_POST["telefono"];
    
    // 🟢 OBTENER EL ESTADO DEL FORMULARIO
    $estado = $_POST["estado"];
    
    $fechaRegistro = date("Y-m-d H:i:s");
    
    // 2. Incluir la lógica
    require_once "logica/Cliente.php";
    
    $cliente = new Cliente("", $nombre, $apellido, $correo, $contraseña, $telefono, $estado, $fechaRegistro);
    
    // 3. Verificar si el correo existe
    if ($cliente->correoExiste()) {
        $correoDuplicado = true;
    } else {
        try {
            // 4. Intentar registrar
            $cliente->registrar();
            $exito = true;
            $_POST = []; // Limpiar formulario
        } catch (Exception $e) {
            $error = true;
            // 🚨 DEBUG ACTIVADO: Mostrar el mensaje de error de la base de datos
            echo "<div class='alert alert-danger text-center mb-3'>❌ Error de la DB: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Cliente - Agenda tu Belleza</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #D1B3FF 0%, #E8D4FF 100%);
      font-family: 'Segoe UI', sans-serif;
    }

    .btn-registrar {
      background-color: #7E57C2;
      color: white;
      border: none;
      transition: all 0.3s ease;
      box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    .btn-registrar:hover {
      background-color: #9C77E8;
      transform: scale(1.04);
      box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }

    .card-form {
      background-color: rgba(255,255,255,0.93);
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .titulo {
      color: #7E57C2;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <div class="position-absolute" style="top: 25px; left: 40px;">
    <div class="rounded-circle overflow-hidden shadow-lg" style="width: 100px; height: 100px;">
      <img src="img/logo.png" alt="Logo Agenda tu Belleza" style="width:100%; height:100%; object-fit:cover;">
    </div>
  </div>

  <div class="col-md-8 col-lg-5 p-4 card-form">
    <h2 class="text-center titulo mb-4">Registrar nuevo cliente</h2>
    <form method="POST" enctype="multipart/form-data" autocomplete="off">

      <div class="mb-3">
          <label class="form-label fw-semibold">Nombre</label>
          <input type="text" name="nombre" class="form-control" autocomplete="off" required>
      </div>

      <div class="mb-3">
          <label class="form-label fw-semibold">Apellido</label>
          <input type="text" name="apellido" class="form-control" autocomplete="off" required>
      </div>
        
      <div class="mb-3">
          <label class="form-label fw-semibold">Correo electronico</label>
          <input type="email" name="correo" class="form-control" autocomplete="off" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Contraseña</label>
        <input type="password" name="contraseña" class="form-control" autocomplete="new-password" required>
      </div>

       <div class="mb-3">
          <label class="form-label fw-semibold">Telefono</label>
          <input type="number" name="telefono" class="form-control" autocomplete="off" required> 
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Estado</label>
        <select name="estado" class="form-select" required> 
          <option value="1" selected>Activo</option>
          <option value="0">Inactivo</option>
        </select>
      </div>

      <?php if ($exito): ?>
        <div class="alert alert-success text-center mb-3">
          ✅ ¡Cliente registrado exitosamente!
        </div>
      <?php elseif ($correoDuplicado): ?>
        <div class="alert alert-danger text-center mb-3">
          ⚠️ El correo ya está registrado. Intenta con otro.
        </div>
      <?php elseif ($error): ?>
        <?php endif; ?>

      <button type="submit" name="registrarCliente" class="btn btn-registrar w-100 py-2 fw-semibold">
        Registrar
      </button>
    </form>

    <div class="text-center mt-3">
      <a href="?pid=<?php echo base64_encode('presentacion/autenticarse.php'); ?>" 
         class="text-decoration-none fw-semibold" style="color:#7E57C2;">
        ← Volver al inicio
      </a>
    </div>
  </div>

</body>
</html>