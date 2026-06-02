<?php
include('conexion.php');

$id = $_POST['id'] ?? $_GET['id'];
$nuevoEstado = $_POST['estado'] ?? $_GET['estado'];

// Si es para dar de baja, validamos contraseña
if($nuevoEstado == 0 && isset($_POST['auth_pass'])) {
    $pass = $_POST['auth_pass'];
    $check = mysqli_query($conexion, "SELECT * FROM responsables WHERE id = $id AND contrasena = '$pass'");
    if(mysqli_num_rows($check) == 0) {
        die("<script>alert('Contraseña incorrecta'); window.history.back();</script>");
    }
}

mysqli_query($conexion, "UPDATE responsables SET activo = $nuevoEstado WHERE id = $id");
header("Location: dashboard.php?tab=responsables"); // Ajusta a tu ruta
?>