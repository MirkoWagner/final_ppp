<?php
require 'conexion_mysqli.php';

$trayectos = [];
$mensaje = "";

// Consultamos la tabla trayectos
$sql = "SELECT id_trayecto, nombre, descripcion, imagen FROM trayectos";

$resultado = $conn->query($sql);

if ($resultado) {
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $trayectos[] = $fila;
        }
    } else {
        $mensaje = "<p style='color: orange;'>No hay trayectos cargados.</p>";
    }
    $resultado->free();
} else {
    $mensaje = "<p style='color: red;'>Error en la consulta SQL: " . $conn->error . "</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Trayectos</title>
<style>
    table { width: 90%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background-color: #f2f2f2; }
    img { max-width: 150px; height: auto; border-radius: 5px; }
</style>
</head>
<body>

<h1>Listado de Trayectos</h1>

<?php echo $mensaje; ?>

<?php if (!empty($trayectos)): ?>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($trayectos as $t): ?>
        <tr>
            <td><?php echo htmlspecialchars($t['nombre']); ?></td>
            <td><?php echo htmlspecialchars($t['descripcion']); ?></td>
            <td>
                <?php if (!empty($t['imagen'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($t['imagen']); ?>">
                <?php else: ?>
                    Sin imagen
                <?php endif; ?>
            </td>
            <td>
    <a href="modificar.php?id=<?php echo $t['id_trayecto']; ?>">Editar</a> |
    <a href="bajas.php?id=<?php echo $t['id_trayecto']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este trayecto?');">Eliminar</a>
</td>

        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<hr>
<button><a href="altas.php">Cargar nuevo trayecto</a></button>

<button><a href="../index.php">Volver</a></button>

</body>
</html>
