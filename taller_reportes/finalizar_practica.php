<?php
include("conexion.php");

// 1. Configurar la zona horaria (Ajusta 'America/Mexico_City' según tu ubicación)
date_default_timezone_set('America/Mexico_City');
mysqli_query($conexion, "SET time_zone = '-06:00'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $reporte_id = mysqli_real_escape_string($conexion, $_POST['reporte_id']);
    $responsable_id = mysqli_real_escape_string($conexion, $_POST['responsable_recibe_id']);
    $pass_introducida = $_POST['pass_responsable'];
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
    $devueltos = $_POST['devueltos']; // Array con las cantidades devueltas

    // 2. Verificar la contraseña del responsable
    $sql_resp = "SELECT contrasena FROM responsables WHERE id = '$responsable_id'";
    $res_resp = mysqli_query($conexion, $sql_resp);
    $info_resp = mysqli_fetch_assoc($res_resp);

    if (!$info_resp || $info_resp['contrasena'] !== $pass_introducida) {
        echo "<script>
                alert('Error: Contraseña de responsable incorrecta.');
                window.history.back();
              </script>";
        exit;
    }

    // 3. Obtener la hora actual del servidor corregida
    $hora_termino = date("Y-m-d H:i:s");

    // 4. Iniciar transacción para asegurar que todo se guarde o nada se guarde
    mysqli_begin_transaction($conexion);

    try {
        // Actualizar el reporte principal (Cerrar el vale)
        $sql_update_reporte = "UPDATE reportes SET 
                                hora_termino = '$hora_termino', 
                                responsable_recibe_id = '$responsable_id',
                                observaciones = '$observaciones' 
                                WHERE id = '$reporte_id'";
        mysqli_query($conexion, $sql_update_reporte);

        // 5. Procesar la devolución de materiales y actualizar inventario
        foreach ($devueltos as $relacion_id => $cantidad_que_regresa) {
            $relacion_id = mysqli_real_escape_string($conexion, $relacion_id);
            $cantidad_que_regresa = (int)$cantidad_que_regresa;

            // Obtener el ID del material y la cantidad original para ajustar el stock
            $sql_info = "SELECT material_id, cantidad FROM reporte_materiales WHERE id = '$relacion_id'";
            $res_info = mysqli_query($conexion, $sql_info);
            $info_mat = mysqli_fetch_assoc($res_info);

            if ($info_mat) {
                $material_id = $info_mat['material_id'];
                
                // Actualizar la cantidad devuelta en la tabla intermedia
                $sql_upd_rel = "UPDATE reporte_materiales SET cantidad_devuelta = '$cantidad_que_regresa' 
                                WHERE id = '$relacion_id'";
                mysqli_query($conexion, $sql_upd_rel);

                
            }
        }

        // Si todo salió bien, confirmar cambios
        mysqli_commit($conexion);
        echo "<script>
                alert('Práctica finalizada con éxito.');
                window.location.href='dashboard.php';
              </script>";

    } catch (Exception $e) {
        // Si hay error, deshacer cambios
        mysqli_rollback($conexion);
        echo "Error al finalizar: " . $e->getMessage();
    }
} else {
    header("Location: dashboard.php");
}

mysqli_close($conexion);
?>