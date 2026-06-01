<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $cuenta = mysqli_real_escape_string($conexion, $_POST['cuenta']);
    $grado  = mysqli_real_escape_string($conexion, $_POST['grado']);
    $grupo  = mysqli_real_escape_string($conexion, $_POST['grupo']);

    if(!empty($nombre) && !empty($cuenta)){
        $sql = "INSERT INTO estudiantes (nombre, numero_cuenta, grado, grupo) 
                VALUES ('$nombre', '$cuenta', '$grado', '$grupo')";

        if (mysqli_query($conexion, $sql)) {
            echo "<script>
                    alert('Estudiante guardado con éxito');
                    window.location.href='/taller_reportes/dashboard.php?tab=estu';
                  </script>";
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
}
mysqli_close($conexion);
?>