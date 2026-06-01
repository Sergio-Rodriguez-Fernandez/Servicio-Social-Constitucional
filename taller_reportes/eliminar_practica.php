<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // Iniciamos una transacción para asegurar que se borre todo o nada
    mysqli_begin_transaction($conexion);

    try {
        // 1. Primero eliminamos los materiales asociados en la tabla intermedia
        // Esto es necesario por las llaves foráneas
        $sql_materiales = "DELETE FROM reporte_materiales WHERE reporte_id = $id";
        mysqli_query($conexion, $sql_materiales);

        // 2. Luego eliminamos el reporte principal
        $sql_reporte = "DELETE FROM reportes WHERE id = $id";
        mysqli_query($conexion, $sql_reporte);

        // Si todo salió bien, confirmamos los cambios
        mysqli_commit($conexion);

        echo "<script>
                alert('Práctica eliminada correctamente.');
                window.location.href='dashboard.php?tab=curso';
              </script>";

    } catch (Exception $e) {
        // Si hay un error, deshacemos los cambios
        mysqli_rollback($conexion);
        echo "<script>
                alert('Error al intentar eliminar la práctica.');
                window.location.href='dashboard.php?tab=curso';
              </script>";
    }

} else {
    // Si no llega un ID, regresamos al dashboard
    header("Location: dashboard.php");
}

mysqli_close($conexion);
?>