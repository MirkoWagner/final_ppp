<?php
require 'conexion_mysqli.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    // Imagen
    $imagen = null;
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    if (!empty($nombre) && !empty($descripcion) && $imagen !== null) {

        $sql = "INSERT INTO trayectos (nombre, descripcion, imagen) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Primero pasamos parámetros vacíos para que PHP acepte el blob
        $null = NULL;
        $stmt->bind_param("ssb", $nombre, $descripcion, $null);

        // Enviamos el blob correctamente
        $stmt->send_long_data(2, $imagen);

        if ($stmt->execute()) {
            $mensaje = "Trayecto guardado correctamente.";
        } else {
            $mensaje = "Error al guardar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $mensaje = "Complete todos los campos e incluya una imagen.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alta de Trayectos</title>
</head>
<body>

<h2>Cargar Trayecto</h2>

<?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

<form action="" method="POST" enctype="multipart/form-data">

    <label for="nombre">Nombre:</label><br>
    <input type="text" name="nombre" id="nombre" required><br><br>

    <label for="descripcion">Descripción:</label><br>
    <textarea name="descripcion" id="descripcion" rows="4" required></textarea><br><br>

    <label for="imagen">Imagen:</label><br>
    <input type="file" name="imagen" id="imagen" accept="image/*" required><br><br>

    <button type="submit">Guardar Trayecto</button>
</form>
<button><a href="../index.php">Volver</a></button>
</body>
</html>