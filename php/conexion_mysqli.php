<?php
// Define las constantes de conexión
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Contraseña vacía por defecto en XAMPP
define('DB_NAME', 'cfp61');

// Crea una nueva conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica si la conexión falló
if ($conn->connect_errno) {
    // Si hay un error, muestra el mensaje y detiene el script
    echo "Fallo al conectar a MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
    exit();
}

// Configura el conjunto de caracteres a UTF-8 (importante para acentos y ñ)
$conn->set_charset("utf8mb4");
?>
