<?php
// Incluye la conexión a la base de datos
require 'conexion_mysqli.php';

// Verifica que hayan pasado un ID por la URL
if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

// Convierte el ID en entero por seguridad
$id = intval($_GET['id']);

// Query para eliminar el trayecto
$sql = "DELETE FROM trayectos WHERE id_trayecto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecuta el delete
if ($stmt->execute()) {
    // Redirige con mensaje de éxito
    header("Location: lista_trayectos.php?msg=eliminado");
    exit();
} else {
    // Si hay error lo muestra
    echo "Error al eliminar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
