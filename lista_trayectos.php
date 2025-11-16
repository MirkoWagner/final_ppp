<?php
require './php/conexion_mysqli.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/lista.css">
</head>


<body>
    <header class="py-3 bg-white shadow-sm">
        <div class="container d-flex align-items-center gap-3">
            <img src="./img/asd.jpg" alt="" width="80">
            <h1 class="m-0">CFP N°61 - La Criolla</h1>
        </div>
    </header>

    <div class="subheader py-4 text-center bg-primary text-white">
        <h2 class="m-0">Lista de nuestros trayectos</h2>
    </div>

    <div class="container">

        <?= $mensaje ?>

        <?php if (!empty($trayectos)): ?>

            <div class="table-responsive shadow-sm">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trayectos as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['nombre']); ?></td>
                                <td><?= htmlspecialchars($t['descripcion']); ?></td>
                                <td>
                                    <?php if (!empty($t['imagen'])): ?>
                                        <img class="trayecto-img"
                                             src="data:image/jpeg;base64,<?= base64_encode($t['imagen']); ?>">
                                    <?php else: ?>
                                        <span class="text-secondary">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary px-4">Volver</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>