<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['m_ids'])) {
    $reporte_id = intval($_POST['reporte_id']);
    $ids = $_POST['m_ids'];
    $cants = $_POST['m_cants'];

    for ($i = 0; $i < count($ids); $i++) {
        $m_id = intval($ids[$i]);
        $cant_nueva = intval($cants[$i]);

        // 1. Verificar si el material ya está en este reporte
        $check = mysqli_query($conexion, "SELECT id, cantidad FROM reporte_materiales 
                                         WHERE reporte_id = '$reporte_id' 
                                         AND material_id = '$m_id'");
        
        if (mysqli_num_rows($check) > 0) {
            // 2. Si ya existe, sumamos la cantidad nueva a la actual
            $row_existente = mysqli_fetch_assoc($check);
            $nueva_cantidad_total = $row_existente['cantidad'] + $cant_nueva;
            $relacion_id = $row_existente['id'];
            
            mysqli_query($conexion, "UPDATE reporte_materiales 
                                     SET cantidad = '$nueva_cantidad_total' 
                                     WHERE id = '$relacion_id'");
        } else {
            // 3. Si no existe, lo insertamos normalmente
            mysqli_query($conexion, "INSERT INTO reporte_materiales (reporte_id, material_id, cantidad) 
                                     VALUES ('$reporte_id', '$m_id', '$cant_nueva')");
        }
    }

    echo "<script>alert('Materiales actualizados correctamente.'); window.location.href='dashboard.php?tab=curso';</script>";
} else {
    header("Location: dashboard.php?tab=curso");
}
?>