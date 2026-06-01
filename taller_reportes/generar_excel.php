<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inicio = mysqli_real_escape_string($conexion, $_POST['f_inicio']);
    $fin = mysqli_real_escape_string($conexion, $_POST['f_fin']);

    if (ob_get_length()) ob_clean();

    $nombre_archivo = "Reporte_Taller_" . $inicio . "_al_\" . $fin . \".xls";

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=$nombre_archivo");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1' style='font-family:Arial; border-collapse:collapse;'>";
    
    // Título (Aumentamos colspan de 12 a 14)
    echo "<tr>
            <th colspan='14' style='background:#1a4d2e; color:white; font-size:16pt; height:45px; text-align:center;'>
                REPORTE DETALLADO DE TALLER FIME
            </th>
          </tr>";
    
    // Encabezados
    echo "<tr style='background:#333; color:white; font-weight:bold; text-align:center;'>
            <th>FECHA</th>
            <th>CUENTA</th>
            <th>ALUMNO</th>
            <th>MESA</th>
            <th>MÁQUINA</th>
            <th>PRÁCTICA</th>
            <th>PROFESOR</th>
            <th>MATERIALES (ENTREGADO -> DEVUELTO)</th>
            <th>OBSERVACIONES</th>
            <th>RESP. SALIDA</th>
            <th>RESP. RECIBE</th>
            <th>INICIO</th>
            <th>FIN</th>
            <th>ESTADO</th>
          </tr>";

    // CONSULTA CON DOBLE JOIN PARA EXCEL
    $query = "SELECT r.*, e.nombre as alumno, e.numero_cuenta, 
              res1.nombre as resp_salida, 
              res2.nombre as resp_entrada
              FROM reportes r
              JOIN estudiantes e ON r.estudiante_id = e.id
              LEFT JOIN responsables res1 ON r.responsable_salida_id = res1.id
              LEFT JOIN responsables res2 ON r.responsable_recibe_id = res2.id
              WHERE DATE(r.hora_inicio) BETWEEN '$inicio' AND '$fin'
              ORDER BY r.hora_inicio ASC";

    $resultado = mysqli_query($conexion, $query);

    while ($row = mysqli_fetch_assoc($resultado)) {
        // Lógica de materiales
        $rep_id = $row['id'];
        $m_query = mysqli_query($conexion, "SELECT rm.*, m.nombre FROM reporte_materiales rm JOIN materiales m ON rm.material_id = m.id WHERE rm.reporte_id = $rep_id");
        
        $lista_materiales = "";
        $es_completo = true;
        while($m = mysqli_fetch_assoc($m_query)){
            $lista_materiales .= "- " . $m['nombre'] . ": (" . $m['cantidad'] . " -> " . $m['cantidad_devuelta'] . ")\n";
            if($m['cantidad'] != $m['cantidad_devuelta']) $es_completo = false;
        }

        echo "<tr>";
        echo "<td style='text-align:center;'>".date("d/m/Y", strtotime($row['hora_inicio']))."</td>";
        echo "<td style='mso-number-format:\"@\";'>".$row['numero_cuenta']."</td>";
        echo "<td>".mb_strtoupper($row['alumno'])."</td>";
        echo "<td style='text-align:center;'>".$row['mesa']."</td>";
        echo "<td style='text-align:center;'>".$row['maquina']."</td>";
        echo "<td>".$row['practica']."</td>";
        echo "<td>".($row['nombre_profesor_opcional'] ?: '-')."</td>";
        echo "<td style='white-space:pre-wrap;'>".trim($lista_materiales)."</td>";
        echo "<td>".($row['observaciones'] ?: '-')."</td>";
        
        // NUEVAS COLUMNAS EXCEL
        echo "<td>".($row['resp_salida'] ?: '-')."</td>";
        echo "<td>".($row['resp_entrada'] ?: '-')."</td>";
        
        echo "<td style='text-align:center;'>".date("H:i", strtotime($row['hora_inicio']))."</td>";
        echo "<td style='text-align:center;'>".($row['hora_termino'] ? date("H:i", strtotime($row['hora_termino'])) : '--:--')."</td>";
        echo "<td style='text-align:center;'>".($es_completo ? 'COMPLETO' : 'FALTANTE')."</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>