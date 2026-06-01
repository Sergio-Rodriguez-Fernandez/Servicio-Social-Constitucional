<?php
// Configuración de fechas para el filtro (por defecto el mes actual)
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h1 style="color: #333;">Historial de Prácticas</h1>
    
    <div style="display:flex; gap:15px; align-items:end;">
        <div style="background: #fdfdfd; padding: 10px; border-radius: 8px; border: 1px solid #eee;">
            <label style="font-size:0.75rem; display:block; color:#666; font-weight: bold; margin-bottom: 4px;">BUSCAR ESTUDIANTE / CUENTA:</label>
            <input type="text" id="bus-historial" onkeyup="filterHistorial()" placeholder="Nombre o No. de cuenta..." style="padding:8px; border:1px solid #ccc; border-radius:4px; outline: none; width: 250px;">
        </div>

        <form action="dashboard.php" method="GET" style="display:flex; gap:12px; align-items:end; background: #fdfdfd; padding: 10px; border-radius: 8px; border: 1px solid #eee;">
            <input type="hidden" name="tab" value="historial">
            <div>
                <label style="font-size:0.75rem; display:block; color:#666; font-weight: bold; margin-bottom: 4px;">DESDE:</label>
                <input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>" style="padding:8px; border:1px solid #ccc; border-radius:4px; outline: none;">
            </div>
            <div>
                <label style="font-size:0.75rem; display:block; color:#666; font-weight: bold; margin-bottom: 4px;">HASTA:</label>
                <input type="date" name="fecha_fin" value="<?php echo $fecha_fin; ?>" style="padding:8px; border:1px solid #ccc; border-radius:4px; outline: none;">
            </div>
            <button type="submit" class="btn btn-blue" style="background-color: #007bff; color: white; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold;">🔍 Filtrar</button>
            
            <button type="button" class="btn" style="background:#1a4d2e; color:white; font-weight:bold; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer;" onclick="exportarExcel()">
                📊 Reporte Excel
            </button>
        </form>
    </div>
</div>

<div class="card" style="padding:0; overflow-x:auto; border:1px solid #e0e0e0; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <table id="table-historial" style="width:100%; border-collapse:collapse; font-size:0.85rem; min-width: 1400px;">
        <thead>
            <tr style="background:#f1f3f5; border-bottom:2px solid #dee2e6; color: #495057;">
                <th style="padding:15px; text-align:left; width: 90px;">FECHA</th>
                <th style="padding:15px; text-align:left; width: 200px;">ESTUDIANTE</th>
                <th style="padding:15px; text-align:center; width: 70px;">MESA</th>
                <th style="padding:15px; text-align:center; width: 80px;">MÁQUINA</th>
                <th style="padding:15px; text-align:left; width: 220px;">PRÁCTICA / PROFESOR</th>
                <th style="padding:15px; text-align:left;">MATERIALES (SOLICITADO → DEVUELTO)</th>
                <th style="padding:15px; text-align:left; width: 150px;">OBSERVACIONES</th>
                <th style="padding:15px; text-align:left; width: 110px;">ENTREGÓ</th>
                <th style="padding:15px; text-align:left; width: 110px;">RECIBIÓ</th>
                <th style="padding:15px; text-align:center; width: 100px;">ESTADO</th>
                <th style="padding:15px; text-align:center; width: 100px;">HORARIO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $query_h = "SELECT r.*, e.nombre as alumno, e.numero_cuenta,
                        res1.nombre as nombre_entrega, 
                        res2.nombre as nombre_recibe
                        FROM reportes r
                        JOIN estudiantes e ON r.estudiante_id = e.id
                        LEFT JOIN responsables res1 ON r.responsable_salida_id = res1.id
                        LEFT JOIN responsables res2 ON r.responsable_recibe_id = res2.id
                        WHERE DATE(r.hora_inicio) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                        ORDER BY r.hora_inicio DESC";
            
            $res_h = mysqli_query($conexion, $query_h);
            
            if(mysqli_num_rows($res_h) > 0):
                while($rh = mysqli_fetch_assoc($res_h)):
                    $rep_id = $rh['id'];
                    $check_m = mysqli_query($conexion, "SELECT * FROM reporte_materiales WHERE reporte_id = $rep_id AND cantidad != cantidad_devuelta");
                    $es_completo = (mysqli_num_rows($check_m) == 0);
            ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:15px; white-space:nowrap; vertical-align: top; font-weight: 500;">
                    <?php echo date("d/m/y", strtotime($rh['hora_inicio'])); ?>
                </td>
                
                <td style="padding:15px; vertical-align: top;">
                    <div style="font-weight:bold; color: #212529;"><?php echo htmlspecialchars($rh['alumno']); ?></div>
                    <div style="color:#6c757d; font-size: 0.75rem;">No. <?php echo htmlspecialchars($rh['numero_cuenta']); ?></div>
                </td>

                <td style="padding:15px; vertical-align: top; text-align: center; font-weight: bold; color: #555;">
                    <?php echo htmlspecialchars($rh['mesa'] ?: '-'); ?>
                </td>

                <td style="padding:15px; vertical-align: top; text-align: center; font-weight: bold; color: #555;">
                    <?php echo htmlspecialchars($rh['maquina'] ?: '-'); ?>
                </td>
                
                <td style="padding:15px; vertical-align: top;">
                    <div style="font-weight:bold;">
                        <?php echo !empty($rh['practica']) ? htmlspecialchars($rh['practica']) : '<span style="color:#adb5bd; font-weight:normal;">(Sin nombre)</span>'; ?>
                    </div>
                    <div style="color:#007bff; font-size: 0.75rem; margin-top: 2px;">
                        Prof: <?php echo htmlspecialchars($rh['nombre_profesor_opcional'] ?: 'N/A'); ?>
                    </div>
                </td>
                
                <td style="padding:15px; vertical-align: top;">
                    <ul style="margin:0; padding-left:18px; font-size:0.8rem; color: #495057; line-height: 1.4;">
                        <?php 
                        $mat_h = mysqli_query($conexion, "SELECT rm.*, m.nombre FROM reporte_materiales rm JOIN materiales m ON rm.material_id = m.id WHERE rm.reporte_id = $rep_id");
                        while($mh = mysqli_fetch_assoc($mat_h)):
                        ?>
                            <li style="<?php echo ($mh['cantidad'] != $mh['cantidad_devuelta']) ? 'color:#dc3545; font-weight:bold;' : ''; ?>">
                                <?php echo htmlspecialchars($mh['nombre']) . ": " . $mh['cantidad'] . " → " . $mh['cantidad_devuelta']; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </td>
                
                <td style="padding:15px; vertical-align: top; color:#6c757d; font-style:italic; line-height: 1.3;">
                    <?php echo !empty($rh['observaciones']) ? htmlspecialchars($rh['observaciones']) : '-'; ?>
                </td>
                
                <td style="padding:15px; vertical-align: top; color:#495057;">
                    <?php echo htmlspecialchars($rh['nombre_entrega'] ?: '-'); ?>
                </td>
                
                <td style="padding:15px; vertical-align: top; color:#495057;">
                    <?php echo $rh['nombre_recibe'] ? htmlspecialchars($rh['nombre_recibe']) : '<span style="color:#ffa000; font-size:0.75rem; font-weight:bold;">⏳ PENDIENTE</span>'; ?>
                </td>
                
                <td style="padding:15px; text-align:center; vertical-align: top;">
                    <?php if($es_completo): ?>
                        <span style="background:#d4edda; color:#155724; padding:5px 8px; border-radius:5px; font-size:0.7rem; font-weight:bold; display: inline-block;">COMPLETO</span>
                    <?php else: ?>
                        <span style="background:#f8d7da; color:#721c24; padding:5px 8px; border-radius:5px; font-size:0.7rem; font-weight:bold; display: inline-block;">FALTANTE</span>
                    <?php endif; ?>
                </td>
                
                <td style="padding:15px; text-align:center; vertical-align: top; white-space: nowrap;">
                    <div style="color:#212529; font-weight: bold;">De <?php echo date("H:i", strtotime($rh['hora_inicio'])); ?></div>
                    <div style="color:#212529; font-weight: bold; font-size: 0.85rem;">
                        a <?php echo ($rh['hora_termino']) ? date("H:i", strtotime($rh['hora_termino'])) : '--:--'; ?>
                    </div>
                </td>
            </tr>
            <?php endwhile; 
            else: ?>
            <tr>
                <td colspan="11" style="padding:50px; text-align:center; color:#adb5bd;">No se encontraron registros.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<form id="formExcel" action="generar_excel.php" method="POST" style="display:none;">
    <input type="hidden" name="f_inicio" value="<?php echo $fecha_inicio; ?>">
    <input type="hidden" name="f_fin" value="<?php echo $fecha_fin; ?>">
</form>

<script>
function filterHistorial() {
    var input = document.getElementById("bus-historial");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("table-historial");
    var tr = table.getElementsByTagName("tr");

    // Empezamos en 1 para omitir el encabezado <thead>
    for (var i = 1; i < tr.length; i++) {
        // La columna [1] contiene tanto el Nombre como el Número de Cuenta
        var td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            var txtValue = td.textContent || td.innerText;
            // Si el texto de la celda contiene lo que escribió el usuario, se muestra la fila
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function exportarExcel() {
    document.getElementById('formExcel').submit();
}
</script>