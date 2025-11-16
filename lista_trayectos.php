<?php
require './php/conexion_mysqli.php'; // Conexión a la base de datos

$trayectos = []; // Acá guardaremos todos los trayectos obtenidos
$mensaje = "";

// Consultamos todos los trayectos de la tabla
$sql = "SELECT id_trayecto, nombre, descripcion, imagen FROM trayectos";

$resultado = $conn->query($sql);

if ($resultado) {
    // Si hay resultados
    if ($resultado->num_rows > 0) {

        // Recorremos cada fila y la guardamos en el array
        while ($fila = $resultado->fetch_assoc()) {
            $trayectos[] = $fila;
        }

    } else {
        // Si no hay registros cargados
        $mensaje = "<p style='color: orange;'>No hay trayectos cargados.</p>";
    }

    // Liberamos memoria del resultado
    $resultado->free();

} else {
    // Error en la consulta SQL
    $mensaje = "<p style='color: red;'>Error en la consulta SQL: " . $conn->error . "</p>";
}

// Cerramos la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Trayectos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="./css/lista.css">
</head>


<body>
    <!-- Encabezado con imagen y título -->
    <header class="py-3 bg-white shadow-sm">
        <div class="container d-flex align-items-center gap-3">
            <img src="./img/asd.jpg" alt="" width="80">
            <h1 class="m-0">CFP N°61 - La Criolla</h1>
        </div>
    </header>

    <!-- Subtítulo -->
    <div class="subheader py-4 text-center bg-primary text-white">
        <h2 class="m-0">Lista de nuestros trayectos</h2>
    </div>

    <div class="container">

        <!-- Muestra mensaje de error o “no hay datos” -->
        <?= $mensaje ?>

        <!-- Si hay trayectos cargados, mostramos la tabla -->
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
                        <!-- Recorremos cada trayecto para mostrarlo -->
                        <?php foreach ($trayectos as $t): ?>
                            <tr>
                                <!-- Nombre -->
                                <td><?= htmlspecialchars($t['nombre']); ?></td>

                                <!-- Descripción -->
                                <td><?= htmlspecialchars($t['descripcion']); ?></td>

                                <!-- Imagen -->
                                <td>
                                    <?php if (!empty($t['imagen'])): ?>
                                        <!-- Si hay imagen, la convertimos a base64 para mostrar -->
                                        <img class="trayecto-img"
                                             src="data:image/jpeg;base64,<?= base64_encode($t['imagen']); ?>">
                                    <?php else: ?>
                                        <!-- Si no hay imagen, mostramos texto -->
                                        <span class="text-secondary">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

        <?php endif; ?>

        <!-- Botón volver -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary px-4">Volver</a>
        </div>

    </div>

    <!-- JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
