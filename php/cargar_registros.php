<?php
require 'conexion_mysqli.php'; // conexión

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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar Mensaje</title>
</head>
<body>

<h2>Registrar Consulta / Mensaje</h2>

<?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

<form action="" method="POST">

    <label for="nombre">Nombre:</label><br>
    <input type="text" name="nombre" id="nombre" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="mensaje">Mensaje:</label><br>
    <textarea name="mensaje" id="mensaje" rows="4" required></textarea><br><br>

    <label for="id_trayectos_fk">Seleccione un Trayecto:</label><br>
    <select name="id_trayectos_fk" id="id_trayectos_fk" required>
        <option value="">Seleccione...</option>

        <?php foreach ($trayectos as $t): ?>
            <option value="<?php echo htmlspecialchars($t['id_trayecto']); ?>">
                <?php echo htmlspecialchars($t['nombre']); ?>
            </option>
        <?php endforeach; ?>

    </select><br><br>

    <button type="submit">Guardar Registro</button>
</form>

<hr>
<p><a href="altas_trayectos.php">Ir a cargar Trayectos</a></p>

</body>
</html>
