<?php
// Estas líneas deben ir al principio para que detecten cualquier error desde el inicio
error_reporting(E_ALL);
ini_set('display_errors', 1);


$host = "localhost";
$user = "root";
$pass = "";
$db   = "taller_db";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>