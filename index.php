<?php
require './php/conexion_mysqli.php'; // conexión

$mensaje = '';
$trayectos = [];

// 1. OBTENER LISTA DE TRAYECTOS
$sql_tray = "SELECT id_trayecto, nombre FROM trayectos ORDER BY nombre";
$res = $conn->query($sql_tray);

if ($res) {
  while ($fila = $res->fetch_assoc()) {
    $trayectos[] = $fila;
  }
  $res->free();
} else {
  $mensaje = "Error al cargar trayectos: " . $conn->error;
}

// 2. PROCESAR FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $nombre  = trim($_POST['nombre']);
  $email   = trim($_POST['email']);
  $mensaje_texto = trim($_POST['mensaje']);
  $id_tray = (int) $_POST['id_trayectos_fk'];

  if (!empty($nombre) && !empty($email) && !empty($mensaje_texto) && $id_tray > 0) {

    $sql = "INSERT INTO registros (nombre, email, mensaje, id_trayectos_fk)
                VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $email, $mensaje_texto, $id_tray);

    if ($stmt->execute()) {
      $mensaje = "Registro guardado correctamente.";
    } else {
      $mensaje = "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
  } else {
    $mensaje = "Complete todos los campos.";
  }
}

?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CFP Nº61 La Criolla</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>

  <header class="py-3 text-center">
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <img src="./img/asd.jpg" alt="" width='100px'>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="lista_trayectos.php">Especialidades</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#contacto">Contacto</a>
            </li>
            <li class="nav-item dropdown" id="adminMenu" style="display:none;">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Admin
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="./php/altas.php">Altas</a></li>
                <li><a class="dropdown-item" href="./php/menu.php">Menu</a></li>
                <li><a class="dropdown-item" href="./php/modificar.php">Modificar</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class='col-4'>
          <h1>CFP N°61 - La Criolla</h1>
        </div>
      </div>
    </nav>
  </header>
  <div class="subheader py-2 text-center">
    <h2>Vení a estudiar a la CFP Nº61. Tenemos diversos trayectos</h2>
  </div>
  <div id="carouselExample" class="carousel slide w-100">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="d-flex justify-content-center align-items-center"
          style="height:540px; background:#032070;">
          <img src="./img/inicio.jpg" alt="">
        </div>
      </div>
      <div class="carousel-item">
        <div class="d-flex justify-content-center align-items-center"
          style="height:540px; background:#014ab0;">
          <img src="./img/inicio2.png" alt="">
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <section class="container mt-5" id="contacto">
    <footer>
      <div class="container-fluid">
        <h3 class="text-center mb-4">Contactanos</h3>

        <div class="row g-4">
          <div class="col-md-4">
            <?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

            <form action="" method="POST">

              <label for="nombre">Nombre:</label><br>
              <input type="text" name="nombre" id="nombre" required><br>

              <label for="email">Email:</label><br>
              <input type="email" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required><br>

              <label for="mensaje">Mensaje:</label><br>
              <textarea name="mensaje" id="mensaje" rows="4" required></textarea><br>

              <label for="id_trayectos_fk">Le intereso algun Trayecto:</label><br>
              <select name="id_trayectos_fk" id="id_trayectos_fk">
                <option value="">Seleccione...</option>

                <?php foreach ($trayectos as $t): ?>
                  <option value="<?php echo htmlspecialchars($t['id_trayecto']); ?>">
                    <?php echo htmlspecialchars($t['nombre']); ?>
                  </option>
                <?php endforeach; ?>

              </select><br><br>

              <!-- NUEVO CAMPO PARA EL ROL -->
              <label for="rol">Rol (escribe "admin" para mostrar el menú):</label><br>
              <input type="text" id="rol"><br><br>

              <button type="submit" id="boton_form">Guardar Registro</button>
            </form>
          </div>
          <div class="col-md-4">
            <div class="contact-card">
              <h2>Ubicación</h2>
              <div class="ratio ratio-4x3">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1951.1627960093592!2d-58.11008216543432!3d-31.268387141516936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95adecae2de3210d%3A0x88da2b43f3de696!2sMunicipalidad%20de%20la%20Criolla!5e0!3m2!1ses!2sar!4v1762734228252!5m2!1ses!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <h2>Redes sociales</h2>
            <p>cfplacriolla@gmail.com</p> <br>
            <p>0345 15-412-3356</p> <br>
            <p><a href="https://www.instagram.com/cfp61lacriolla" target="_blank">instagram</a></p>
          </div>
        </div>
      </div>

    </footer>

  </section>

  <script>
    document.getElementById("rol").addEventListener("input", function() {
      const valor = this.value.toLowerCase().trim();

      if (valor === "admin") {
        document.getElementById("adminMenu").style.display = "block";
      } else {
        document.getElementById("adminMenu").style.display = "none";
      }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>