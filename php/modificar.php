<?php
require 'conexion_mysqli.php';

$mensaje = "";

// Si llega con ID, buscamos datos
if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

$id = intval($_GET['id']);

// Si enviaron el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);

    // Imagen nueva opcional
    $nuevaImagen = null;
    $cambiarImagen = false;

    if (!empty($_FILES["imagen"]["tmp_name"])) {
        $nuevaImagen = file_get_contents($_FILES["imagen"]["tmp_name"]);
        $cambiarImagen = true;
    }

    if ($cambiarImagen) {
        // Actualiza también imagen
        $sql = "UPDATE trayectos SET nombre=?, descripcion=?, imagen=? WHERE id_trayecto=?";
        $stmt = $conn->prepare($sql);
        $null = NULL;
        $stmt->bind_param("ssbi", $nombre, $descripcion, $null, $id);
        $stmt->send_long_data(2, $nuevaImagen);
    } else {
        // Mantener imagen anterior
        $sql = "UPDATE trayectos SET nombre=?, descripcion=? WHERE id_trayecto=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
    }

    if ($stmt->execute()) {
        header("Location: lista_trayectos.php?msg=editado");
        exit();
    } else {
        $mensaje = "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener datos actuales
$sql = "SELECT nombre, descripcion, imagen FROM trayectos WHERE id_trayecto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Trayecto no encontrado.");
}

$trayecto = $result->fetch_assoc();
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

<?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

<form method="POST" enctype="multipart/form-data">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($trayecto['nombre']); ?>" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4" required><?php echo htmlspecialchars($trayecto['descripcion']); ?></textarea><br><br>

    <label>Imagen actual:</label><br>
    <img src="data:image/jpeg;base64,<?php echo base64_encode($trayecto['imagen']); ?>" width="200"><br><br>

    <label>Subir nueva imagen (opcional):</label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>
