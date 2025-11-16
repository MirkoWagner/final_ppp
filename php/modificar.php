<?php
// Incluye la conexión a la base de datos
require 'conexion_mysqli.php';

$mensaje = "";

// Verifica que se haya enviado un ID por la URL
if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

// Convierte el ID a entero por seguridad
$id = intval($_GET['id']);

// Si el formulario fue enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Toma los datos enviados
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);

    // Control para imagen nueva (opcional)
    $nuevaImagen = null;
    $cambiarImagen = false;

    // Si subieron una imagen nueva
    if (!empty($_FILES["imagen"]["tmp_name"])) {
        // Guarda la imagen en formato binario
        $nuevaImagen = file_get_contents($_FILES["imagen"]["tmp_name"]);
        $cambiarImagen = true;
    }

    // Si hay nueva imagen → actualiza todo
    if ($cambiarImagen) {
        $sql = "UPDATE trayectos SET nombre=?, descripcion=?, imagen=? WHERE id_trayecto=?";
        $stmt = $conn->prepare($sql);

        // Necesario para enviar datos binarios (BLOB)
        $null = NULL;
        $stmt->bind_param("ssbi", $nombre, $descripcion, $null, $id);
        $stmt->send_long_data(2, $nuevaImagen);

    } else {
        // Sin nueva imagen → actualiza solo texto
        $sql = "UPDATE trayectos SET nombre=?, descripcion=? WHERE id_trayecto=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
    }

    // Ejecuta la consulta
    if ($stmt->execute()) {
        // Redirige si todo salió bien
        header("Location: lista_trayectos.php?msg=editado");
        exit();
    } else {
        // Muestra mensaje de error
        $mensaje = "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}

// Obtiene los datos actuales del trayecto para mostrar en el formulario
$sql = "SELECT nombre, descripcion, imagen FROM trayectos WHERE id_trayecto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Si no existe el trayecto
if ($result->num_rows === 0) {
    die("Trayecto no encontrado.");
}

// Almacena los datos del registro
$trayecto = $result->fetch_assoc();

// Cierra conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Trayecto</title>
</head>
<body>

<h2>Editar Trayecto</h2>

<!-- Muestra mensaje de error si existe -->
<?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

<!-- Formulario de edición -->
<form method="POST" enctype="multipart/form-data">

    <!-- Nombre -->
    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($trayecto['nombre']); ?>" required><br><br>

    <!-- Descripción -->
    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4" required><?php echo htmlspecialchars($trayecto['descripcion']); ?></textarea><br><br>

    <!-- Imagen actual -->
    <label>Imagen actual:</label><br>
    <img src="data:image/jpeg;base64,<?php echo base64_encode($trayecto['imagen']); ?>" width="200"><br><br>

    <!-- Subir nueva imagen -->
    <label>Subir nueva imagen (opcional):</label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <!-- Botón guardar -->
    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>
