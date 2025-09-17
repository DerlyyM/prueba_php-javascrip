<?php
// archivo de conexión - conexion.php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "elementos";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$bd;charset=utf8", $usuario, $clave);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // (lo dejamos comentado para pruebas)
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
