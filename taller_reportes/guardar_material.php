<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapamos el texto para evitar errores con comillas (ej. Llave 1/4")
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);

    $sql = "INSERT INTO materiales (nombre) VALUES ('$nombre')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('Material agregado al inventario');
                window.location.href='dashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
mysqli_close($conexion);
?>