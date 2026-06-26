<?php 
// tab_en_curso.php - Versión Completa con corrección de Grid y Deprecated de PHP
?>
<style>
    .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .search-box input { padding: 10px 15px; border-radius: 20px; border: 1px solid #ddd; width: 300px; outline: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    .btn-auth-fast { padding: 10px 15px; border-radius: 5px; border: none; cursor: pointer; font-weight: bold; transition: 0.3s; font-size: 0.85rem; }
    .auth-off { background: #28a745; color: white; }
    .auth-on { background: #dc3545; color: white; }

    .modal-auth { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-auth-content { background: white; padding: 25px; border-radius: 10px; width: 320px; text-align: center; }
    .modal-auth-content select, .modal-auth-content input { width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }

    /* Clase contenedora original para ordenar horizontalmente las tarjetas */
    .grid-practicas { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 20px; width: 100%; }
</style>

<div class="header-flex">
    <div style="display: flex; align-items: center; gap: 15px;">
        <h1>Prácticas Activas</h1>
        <button id="btn-sesion-global" class="btn-auth-fast auth-off" onclick="controlarModalAuth()">
            ⚡ AUTORIZACIÓN RÁPIDA (15 MIN)
        </button>
    </div>
    
    <div class="search-box">
        <input type="text" id="input-buscador" placeholder="Buscar por nombre o cuenta..." onkeyup="filtrarTarjetas()">
    </div>
</div>

<div class="grid-practicas" id="contenedor-tarjetas">
    <?php 
    $sql_c = "SELECT r.*, e.nombre as alumno, e.numero_cuenta, e.grado, e.grupo 
              FROM reportes r 
              JOIN estudiantes e ON r.estudiante_id = e.id 
              WHERE r.hora_termino IS NULL ORDER BY r.hora_inicio DESC";
    $res_c = mysqli_query($conexion, $sql_c);
    
    if(mysqli_num_rows($res_c) == 0): ?>
        <div class='card full-width' style="grid-column: 1 / -1;">
            <p style='text-align:center; padding:40px; color: #666;'>No hay préstamos activos en este momento.</p>
        </div>
    <?php 
    else:
        while($row = mysqli_fetch_assoc($res_c)): 
            $r_id = $row['id'];
            $txt_practica = !empty($row['practica']) ? $row['practica'] : 'Sin especificar';
            $txt_profesor = !empty($row['nombre_profesor_opcional']) ? $row['nombre_profesor_opcional'] : 'Sin especificar';
            $search_data = strtolower($row['alumno'] . " " . $row['numero_cuenta']);
    ?>
    <div class="card card-item" data-search="<?php echo $search_data; ?>" style="border-left: 5px solid #ffc107; display: flex; flex-direction: column;">
        <form action="finalizar_practica.php" method="POST" class="form-finalizar" id="form-finalizar-<?php echo $r_id; ?>">
            <input type="hidden" name="reporte_id" value="<?php echo $r_id; ?>">
            
            <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <strong style="font-size: 1.1rem; color: #007bff; text-transform: uppercase;"><?php echo $row['alumno']; ?></strong><br>
                    <small>Cuenta: <?php echo $row['numero_cuenta']; ?> | Grupo: <?php echo $row['grado'].$row['grupo']; ?></small>
                </div>
                <div style="text-align: right; background: #f8f9fa; padding: 4px 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <small style="color: #e91e63; font-weight: bold;">📍 M: <?php echo $row['mesa'] ?: '-'; ?> / MQ: <?php echo $row['maquina'] ?: '-'; ?></small>
                </div>
            </div>

            <div style="background: #fffdf2; border: 1px solid #faebcc; padding: 10px; border-radius: 5px; margin-bottom: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <small style="color: #8a6d3b; font-weight: bold; font-size: 0.7rem; display: block; text-transform: uppercase;">PRÁCTICA:</small>
                    <span style="font-size: 0.85rem; color: #333;"><?php echo $txt_practica; ?></span>
                </div>
                <div>
                    <small style="color: #8a6d3b; font-weight: bold; font-size: 0.7rem; display: block; text-transform: uppercase;">PROFESOR:</small>
                    <span style="font-size: 0.85rem; color: #333;"><?php echo $txt_profesor; ?></span>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; border: 1px solid #eee; margin-bottom: 10px;">
                <strong style="font-size: 0.85rem; display: block; margin-bottom: 5px;">Materiales (¿Cuántos regresan?)</strong>
                <?php 
                // Añadimos rm.cantidad_devuelta para poder leer las cantidades guardadas
                $sql_m = "SELECT rm.id, rm.cantidad, rm.cantidad_devuelta, m.nombre 
                          FROM reporte_materiales rm 
                          JOIN materiales m ON rm.material_id = m.id 
                          WHERE rm.reporte_id = $r_id";
                $res_m = mysqli_query($conexion, $sql_m);
                while($m = mysqli_fetch_assoc($res_m)): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; font-size: 0.85rem;">
                        <span><?php echo $m['nombre']; ?> (Llevó: <?php echo $m['cantidad']; ?>)</span>
                        <input type="number" name="devueltos[<?php echo $m['id']; ?>]" 
                               value="<?php echo !empty($m['cantidad_devuelta']) ? $m['cantidad_devuelta'] : 0; ?>" 
                               min="0" max="<?php echo $m['cantidad']; ?>" 
                               style="width: 50px; padding: 2px; text-align: center;">
                    </div>
                <?php endwhile; ?>
            </div>

            <div style="margin-bottom: 10px;">
                <textarea name="observaciones" rows="2" style="width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 5px; font-size: 0.85rem;" placeholder="Observaciones de entrega..."><?php echo htmlspecialchars((string)$row['observaciones']); ?></textarea>
            </div>

            <div style="margin-bottom: 10px;">
                <select name="responsable_recibe_id" class="select-resp" required style="width: 100%; padding: 8px; margin-bottom: 5px; font-size: 0.85rem;">
                    <option value="">-- Selecciona quién recibe --</option>
                    <?php 
                    $res_resp = mysqli_query($conexion, "SELECT * FROM responsables WHERE activo = 1 ORDER BY nombre ASC");
                    while($rr = mysqli_fetch_assoc($res_resp)) {
                        $selected = ($row['responsable_recibe_id'] == $rr['id']) ? 'selected' : '';
                        echo "<option value='{$rr['id']}' $selected>Recibe: {$rr['nombre']}</option>";
                    }
                    ?>
                </select>
                <input type="password" name="pass_responsable" class="input-pass" placeholder="Contraseña de responsable" required 
                       style="width: 100%; padding: 8px; font-size: 0.85rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="flex: 2; font-weight: bold;">FINALIZAR</button>
                <button type="button" class="btn btn-blue" style="flex: 1; font-weight: bold; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;" onclick="guardarEntregaParcial(<?php echo $r_id; ?>)">💾</button>
                <button type="button" class="btn btn-blue" style="flex: 1; font-weight: bold; font-size: 1.2rem;" 
                        onclick="abrirModalMateriales(<?php echo $r_id; ?>)">+</button>
                <a href="eliminar_practica.php?id=<?php echo $r_id; ?>" class="btn btn-red" 
                   onclick="return confirm('¿Eliminar vale?')" 
                   style="text-decoration: none; display: flex; align-items: center; justify-content: center;">✖</a>
            </div>
        </form>
    </div>
    <?php endwhile; 
    endif; ?>
</div> <div id="modalAuthGlobal" class="modal-auth">
    <div class="modal-auth-content">
        <h3 style="margin-top:0;">Autorización Rápida</h3>
        <select id="auth_id_global">
            <option value="">-- Responsable --</option>
            <?php 
            $res_resp_m = mysqli_query($conexion, "SELECT * FROM responsables WHERE activo = 1 ORDER BY nombre ASC");
            while($rr = mysqli_fetch_assoc($res_resp_m)) echo "<option value='{$rr['id']}'>{$rr['nombre']}</option>";
            ?>
        </select>
        <input type="password" id="auth_pass_global" placeholder="Contraseña">
        <button class="btn-auth-fast auth-off" style="width:100%; margin-bottom:10px;" onclick="activarSesionGlobal()">ACTIVAR</button>
        <button type="button" onclick="cerrarModalAuth()" style="background:none; border:none; color: #999; cursor:pointer;">Cancelar</button>
    </div>
</div>

<div id="modalMateriales" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:1000; justify-content:center; align-items:center;">
    <div class="card" style="width:90%; max-width:450px; padding:20px; background:white;">
        <h3 style="margin-top:0;">Añadir Material Extra</h3>
        <div style="display:flex; gap:5px; margin-bottom:15px;">
            <input type="text" id="in-mat-modal" list="list-mats-modal" placeholder="Material..." style="flex:2; padding:8px;">
            <input type="number" id="in-cant-modal" value="1" min="1" style="width:60px; padding:8px;">
            <button type="button" class="btn btn-blue" onclick="agregarAFilaModal()">Añadir</button>
        </div>
        <datalist id="list-mats-modal">
            <?php 
            $mats = mysqli_query($conexion, "SELECT * FROM materiales ORDER BY nombre ASC");
            while($m = mysqli_fetch_assoc($mats)) echo "<option value='{$m['nombre']}' data-id='{$m['id']}'>";
            ?>
        </datalist>
        <form action="actualizar_materiales_lote.php" method="POST">
            <input type="hidden" name="reporte_id" id="modal_reporte_id">
            <table style="width:100%; margin-bottom:15px; font-size:0.9rem;">
                <tbody id="cuerpo-modal-mats"></tbody>
            </table>
            <div id="inputs-escondidos"></div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Guardar</button>
                <button type="button" class="btn btn-red" onclick="cerrarModal()" style="flex:1;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function filtrarTarjetas() {
    const busqueda = document.getElementById('input-buscador').value.toLowerCase();
    const tarjetas = document.querySelectorAll('.card-item');
    tarjetas.forEach(t => {
        const info = t.getAttribute('data-search');
        t.style.display = info.includes(busqueda) ? 'block' : 'none';
    });
}

function controlarModalAuth() {
    if(localStorage.getItem('sesion_encurso')) {
        localStorage.removeItem('sesion_encurso');
        alert("Sesión rápida desactivada.");
        location.reload();
    } else {
        document.getElementById('modalAuthGlobal').style.display = 'flex';
    }
}

function cerrarModalAuth() {
    document.getElementById('modalAuthGlobal').style.display = 'none';
}

function activarSesionGlobal() {
    const id = document.getElementById('auth_id_global').value;
    const pass = document.getElementById('auth_pass_global').value;
    if(!id || !pass) return alert("Completa los datos");
    const data = { id: id, pass: pass, exp: new Date().getTime() + (15 * 60 * 1000) };
    localStorage.setItem('sesion_encurso', JSON.stringify(data));
    alert("✅ Autorización activa por 15 min.");
    location.reload();
}

function aplicarAutoRelleno() {
    const sesionStr = localStorage.getItem('sesion_encurso');
    if(sesionStr) {
        const sesion = JSON.parse(sesionStr);
        if(new Date().getTime() > sesion.exp) {
            localStorage.removeItem('sesion_encurso');
            return;
        }
        const btn = document.getElementById('btn-sesion-global');
        if(btn) {
            btn.innerHTML = "🔴 DESACTIVAR AUTORIZACIÓN";
            btn.className = "btn-auth-fast auth-on";
        }
        document.querySelectorAll('.form-finalizar').forEach(f => {
            const s = f.querySelector('.select-resp');
            const p = f.querySelector('.input-pass');
            if(s) s.value = sesion.id;
            if(p) p.value = sesion.pass;
        });
    }
}

function abrirModalMateriales(id) {
    document.getElementById('modalMateriales').style.display = 'flex';
    document.getElementById('modal_reporte_id').value = id;
}

function cerrarModal() {
    document.getElementById('modalMateriales').style.display = 'none';
    document.getElementById('cuerpo-modal-mats').innerHTML = '';
    document.getElementById('inputs-escondidos').innerHTML = '';
}

function agregarAFilaModal() {
    const input = document.getElementById('in-mat-modal');
    const cant = document.getElementById('in-cant-modal');
    const list = document.getElementById('list-mats-modal');
    const option = Array.from(list.options).find(opt => opt.value === input.value);
    if(!option) return alert("Selecciona un material de la lista");
    const rowId = Date.now();
    const htmlTabla = `<tr id="row-${rowId}"><td style="padding:5px;">${input.value}</td><td>x ${cant.value}</td><td><button type="button" onclick="quitarMaterialModal(${rowId})" class="btn btn-red" style="padding:2px 5px;">✖</button></td></tr>`;
    const htmlHidden = `<div id="hid-${rowId}"><input type="hidden" name="m_ids[]" value="${option.dataset.id}"><input type="hidden" name="m_cants[]" value="${cant.value}"></div>`;
    document.getElementById('cuerpo-modal-mats').insertAdjacentHTML('beforeend', htmlTabla);
    document.getElementById('inputs-escondidos').insertAdjacentHTML('beforeend', htmlHidden);
    input.value = '';
}

function quitarMaterialModal(id) {
    document.getElementById('row-' + id).remove();
    document.getElementById('hid-' + id).remove();
}

function guardarEntregaParcial(idTarjeta) {
    const formOriginal = document.getElementById('form-finalizar-' + idTarjeta);
    if (!formOriginal) return;

    const selectResp = formOriginal.querySelector('.select-resp');
    const inputPass = formOriginal.querySelector('.input-pass');

    if (!selectResp || !selectResp.value || !inputPass || !inputPass.value) {
        alert("⚠️ Para realizar un guardado parcial, es obligatorio seleccionar el responsable e ingresar su contraseña.");
        if (selectResp && !selectResp.value) selectResp.focus();
        else if (inputPass) inputPass.focus();
        return;
    }

    const formFantasma = document.createElement('form');
    formFantasma.action = 'finalizar_practica.php';
    formFantasma.method = 'POST';
    formFantasma.style.display = 'none';

    const inputsParaClonar = formOriginal.querySelectorAll(
        'input[name="reporte_id"], textarea[name="observaciones"], input[name^="devueltos["], .select-resp, .input-pass'
    );
    
    inputsParaClonar.forEach(input => {
        const clon = document.createElement('input');
        clon.type = 'hidden';
        clon.name = input.name;
        clon.value = input.value;
        formFantasma.appendChild(clon);
    });

    const inputSoloGuardar = document.createElement('input');
    inputSoloGuardar.type = 'hidden';
    inputSoloGuardar.name = 'solo_guardar';
    inputSoloGuardar.value = '1';
    formFantasma.appendChild(inputSoloGuardar);

    document.body.appendChild(formFantasma);
    formFantasma.submit();
}

aplicarAutoRelleno();
</script>
