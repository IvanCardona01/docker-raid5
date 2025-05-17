<?php
$servername = "mysql";
$username = "user";
$password = "userpass";
$dbname = "app_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Revisar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "¡Conexión exitosa a MySQL!";
$conn->close();
?>
