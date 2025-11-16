<?php
// Conecta a la base de datos
require 'conexion_mysqli.php';

$mensaje = '';

// Si el formulario fue enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtiene los datos enviados
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    // Procesa la imagen enviada
    $imagen = null;
    if (!empty($_FILES['imagen']['tmp_name'])) {
        // Lee la imagen en formato binario (para BLOB)
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    // Verifica que todos los campos estén completos
    if (!empty($nombre) && !empty($descripcion) && $imagen !== null) {

        // Prepara consulta para insertar un nuevo trayecto
        $sql = "INSERT INTO trayectos (nombre, descripcion, imagen) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Para blobs, primero se enlaza como null
        $null = NULL;
        $stmt->bind_param("ssb", $nombre, $descripcion, $null);

        // Envía el contenido binario real de la imagen
        $stmt->send_long_data(2, $imagen);

        // Ejecuta la inserción
        if ($stmt->execute()) {
            $mensaje = "Trayecto guardado correctamente.";
        } else {
            $mensaje = "Error al guardar: " . $stmt->error;
        }

        // Cierra la sentencia
        $stmt->close();

    } else {
        // Algún campo vacío
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

<!-- Muestra el mensaje de éxito o error -->
<?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

<!-- Formulario de alta de trayectos -->
<form action="" method="POST" enctype="multipart/form-data">

    <!-- Campo nombre -->
    <label for="nombre">Nombre:</label><br>
    <input type="text" name="nombre" id="nombre" required><br><br>

    <!-- Campo descripción -->
    <label for="descripcion">Descripción:</label><br>
    <textarea name="descripcion" id="descripcion" rows="4" required></textarea><br><br>

    <!-- Campo imagen -->
    <label for="imagen">Imagen:</label><br>
    <input type="file" name="imagen" id="imagen" accept="image/*" required><br><br>

    <!-- Botón enviar -->
    <button type="submit">Guardar Trayecto</button>
</form>

<!-- Botón para volver al inicio -->
<button><a href="../index.php">Volver</a></button>

</body>
</html>
