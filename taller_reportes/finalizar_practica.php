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

    // 2. Verificar la contraseña del responsable (Obligatoria para ambos casos)
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

    // Iniciar transacción para asegurar consistencia
    mysqli_begin_transaction($conexion);

    try {
        // EVALUACIÓN CRUCIAL: Verificamos si viene la bandera del disquete 💾 (solo guardar)
        if (isset($_POST['solo_guardar']) && $_POST['solo_guardar'] === '1') {
            
            // CASO A: GUARDADO PARCIAL
            // Solo actualizamos las anotaciones y el responsable, dejando la 'hora_termino' intacta (en NULL)
            $sql_upd = "UPDATE reportes 
                        SET observaciones = '$observaciones',
                            responsable_recibe_id = '$responsable_id'
                        WHERE id = '$reporte_id'";
            mysqli_query($conexion, $sql_upd);

        } else {
            
            // CASO B: FINALIZACIÓN DEFINITIVA (Botón "FINALIZAR")
            // Cerramos formalmente el vale asentando la hora actual
            $hora_termino = date("Y-m-d H:i:s");
            $sql_upd = "UPDATE reportes 
                        SET hora_termino = '$hora_termino', 
                            observaciones = '$observaciones',
                            responsable_recibe_id = '$responsable_id'
                        WHERE id = '$reporte_id'";
            mysqli_query($conexion, $sql_upd);

        }

        // 3. Actualizar cantidades devueltas en la tabla intermedia (Aplica para ambos casos)
        if (isset($devueltos) && is_array($devueltos)) {
            foreach ($devueltos as $relacion_id => $cantidad_que_regresa) {
                $relacion_id = mysqli_real_escape_string($conexion, $relacion_id);
                $cantidad_que_regresa = (int)$cantidad_que_regresa;

                // Validamos la existencia de la relación antes de modificar
                $sql_info = "SELECT material_id, cantidad FROM reporte_materiales WHERE id = '$relacion_id'";
                $res_info = mysqli_query($conexion, $sql_info);
                $info_mat = mysqli_fetch_assoc($res_info);

                if ($info_mat) {
                    // Actualizar el valor devuelto actual por el alumno
                    $sql_upd_rel = "UPDATE reporte_materiales 
                                    SET cantidad_devuelta = '$cantidad_que_regresa' 
                                    WHERE id = '$relacion_id'";
                    mysqli_query($conexion, $sql_upd_rel);
                }
            }
        }

        // Si todo salió bien, confirmar los cambios de forma definitiva en la Base de Datos
        mysqli_commit($conexion);

        // 4. Redirección inteligente según la acción realizada
        if (isset($_POST['solo_guardar']) && $_POST['solo_guardar'] === '1') {
            // Si el sistema maneja pestañas por parámetro URL (ej: dashboard.php?tab=en_curso), cámbialo aquí.
            // Si no, recargar usando 'window.history.back()' o volviendo a llamar a la página origen mantiene la pestaña activa.
            echo "<script>
                    alert('Cambios parciales guardados correctamente.');
                    if(window.opener) {
                        window.opener.location.reload();
                        window.close();
                    } else {
                        window.location.href = document.referrer || 'dashboard.php';
                    }
                  </script>";
        } else {
            echo "<script>
                    alert('Práctica finalizada con éxito.');
                    window.location.href='dashboard.php';
                  </script>";
        }

    } catch (Exception $e) {
        // En caso de que ocurra algún fallo imprevisto, deshacer cambios en lote
        mysqli_rollback($conexion);
        echo "Error al procesar la solicitud: " . $e->getMessage();
    }
}
?>
