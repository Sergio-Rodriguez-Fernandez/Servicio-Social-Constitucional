<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reporte_id         = $_POST['reporte_id'];
    $profesor_recibe_id = $_POST['profesor_recibe_id']; // El que RECIBE los materiales
    $pass_input         = $_POST['pass_profesor'];

    // Validar contraseña del profesor que recibe
    $p_check = mysqli_query($conexion, "SELECT contrasena FROM profesores WHERE id = '$profesor_recibe_id'");
    $p_data = mysqli_fetch_assoc($p_check);

    if ($p_data['contrasena'] !== $pass_input) {
        echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
        exit();
    }

    // Actualizar con hora_termino y profesor_recibe_id
    $sql = "UPDATE reportes SET 
            hora_termino = NOW(), 
            profesor_recibe_id = '$profesor_recibe_id' 
            WHERE id = $reporte_id";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Material recibido. Práctica finalizada.'); window.location.href='dashboard.php';</script>";
    }
}
?>