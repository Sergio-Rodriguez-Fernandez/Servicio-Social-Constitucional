<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estudiante_id = $_POST['estudiante_id'];
    $mesa = mysqli_real_escape_string($conexion, $_POST['mesa']);
    $maquina = mysqli_real_escape_string($conexion, $_POST['maquina']);
    $practica = mysqli_real_escape_string($conexion, $_POST['practica']);
    // Nueva variable opcional
    $profesor_titular = mysqli_real_escape_string($conexion, $_POST['nombre_profesor_opcional']);
    
    $responsable_id = $_POST['responsable_salida_id'];
    $pass_intro = $_POST['pass_responsable'];

    // Verificar contraseña del responsable
    $query_resp = mysqli_query($conexion, "SELECT contrasena FROM responsables WHERE id = '$responsable_id'");
    $resp = mysqli_fetch_assoc($query_resp);

    if ($resp['contrasena'] === $pass_intro) {
        // Insertar reporte principal con el nuevo campo del profesor
        $sql = "INSERT INTO reportes (estudiante_id, mesa, maquina, practica, nombre_profesor_opcional, responsable_salida_id, hora_inicio) 
                VALUES ('$estudiante_id', '$mesa', '$maquina', '$practica', '$profesor_titular', '$responsable_id', NOW())";
        
        if (mysqli_query($conexion, $sql)) {
            $reporte_id = mysqli_insert_id($conexion);

            // Insertar los materiales seleccionados
            if (isset($_POST['m_ids'])) {
                $ids = $_POST['m_ids'];
                $cants = $_POST['m_cants'];
                for ($i = 0; $i < count($ids); $i++) {
                    $m_id = $ids[$i];
                    $cant = $cants[$i];
                    mysqli_query($conexion, "INSERT INTO reporte_materiales (reporte_id, material_id, cantidad) VALUES ('$reporte_id', '$m_id', '$cant')");
                }
            }
            echo "<script>alert('Vale generado con éxito'); window.location.href='dashboard.php?tab=curso';</script>";
        }
    } else {
        echo "<script>alert('Contraseña de responsable incorrecta'); window.history.back();</script>";
    }
}
mysqli_close($conexion);
?>