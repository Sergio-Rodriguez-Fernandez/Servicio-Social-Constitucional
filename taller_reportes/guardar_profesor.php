<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre_profe']);
    // Guardamos la contraseña. Nota: En un sistema real se debería usar password_hash
    $pass = mysqli_real_escape_string($conexion, $_POST['pass_profe']);

    $sql = "INSERT INTO profesores (nombre, contrasena) VALUES ('$nombre', '$pass')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('Profesor registrado con éxito');
                window.location.href='dashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
mysqli_close($conexion);
?>