<?php
include("conexion.php"); // Al estar en la raíz junto a dashboard, se queda así

if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $tipo = $_GET['tipo'];
    $tabla = "";
    $redirect = "";

    switch ($tipo) {
        case 'responsable':
            $tabla = "responsables";
            $redirect = "resp";
            break;
        case 'estudiante':
            $tabla = "estudiantes";
            $redirect = "estu";
            break;
        case 'material':
            $tabla = "materiales";
            $redirect = "mat";
            break;
        case 'profesor':
            $tabla = "profesores";
            $redirect = "profe";
            break;
        default:
            die("Error: Tipo no válido");
    }

    $sql = "DELETE FROM $tabla WHERE id = '$id'";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('Eliminado con éxito');
                window.location.href='dashboard.php?tab=$redirect';
              </script>";
    } else {
        echo "<script>
                alert('Error: No se pudo eliminar. El registro puede estar en uso en un reporte.');
                window.location.href='dashboard.php?tab=$redirect';
              </script>";
    }
} else {
    header("Location: dashboard.php");
}
mysqli_close($conexion);
?>