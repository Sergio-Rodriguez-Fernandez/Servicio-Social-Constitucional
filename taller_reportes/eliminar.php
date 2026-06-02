<?php
include('conexion.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($id > 0 && $tipo == 'estudiante') {
    // PASO 1: Buscar el nombre del alumno para el historial antes de que desaparezca
    $res_nom = mysqli_query($conexion, "SELECT nombre FROM estudiantes WHERE id = $id");
    
    if ($row_nom = mysqli_fetch_assoc($res_nom)) {
        $nombre_fijo = mysqli_real_escape_string($conexion, $row_nom['nombre']);

        // PASO 2: "Congelar" el nombre en el reporte como texto
        // Así el reporte ya no dependerá de si el alumno existe o no
        mysqli_query($conexion, "UPDATE reportes SET nombre_alumno_respaldo = '$nombre_fijo' WHERE estudiante_id = $id");

        // PASO 3: Borrar al alumno
        // Como pusimos la columna como NULL en el paso anterior de SQL, ya NO dará Fatal Error
        $sql_delete = "DELETE FROM estudiantes WHERE id = $id";
        
        if (mysqli_query($conexion, $sql_delete)) {
            header("Location: dashboard.php?tab=estudiantes&msg=ok");
        } else {
            echo "Error al eliminar: " . mysqli_error($conexion);
        }
    }
}
?>