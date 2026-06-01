<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);

    $sql = "UPDATE materiales SET nombre = '$nombre' WHERE id = $id";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('Material actualizado correctamente');
                window.location.href='dashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
mysqli_close($conexion);
?>