<?php
include("conexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $pass = mysqli_real_escape_string($conexion, $_POST['contrasena']);
    $sql = "INSERT INTO responsables (nombre, contrasena) VALUES ('$nombre', '$pass')";
    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Responsable registrado'); window.location.href='dashboard.php?tab=resp';</script>";
    }
}
mysqli_close($conexion);
?>