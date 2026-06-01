<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $cuenta = mysqli_real_escape_string($conexion, $_POST['cuenta']);
    $grado  = mysqli_real_escape_string($conexion, $_POST['grado']);
    $grupo  = mysqli_real_escape_string($conexion, $_POST['grupo']);

    $sql = "UPDATE estudiantes SET 
            nombre = '$nombre', 
            numero_cuenta = '$cuenta', 
            grado = '$grado', 
            grupo = '$grupo' 
            WHERE id = '$id'";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('Datos actualizados correctamente');
                window.location.href='dashboard.php';
              </script>";
    } else {
        // Esto evitará que la pantalla se quede en blanco si hay error de SQL
        die("Error al actualizar: " . mysqli_error($conexion));
    }
} else {
    header("Location: dashboard.php");
}
mysqli_close($conexion);
?>