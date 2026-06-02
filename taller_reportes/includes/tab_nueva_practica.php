<style>
    .form-header { margin-bottom: 25px; border-bottom: 2px solid var(--primary); pb: 10px; }
    .section-title { font-size: 1.1rem; color: var(--dark); font-weight: bold; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .input-group { display: flex; flex-direction: column; }
    .input-group label { margin-bottom: 5px; font-size: 0.9rem; color: #555; }
    .full-row { grid-column: span 2; }
    .material-box { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; }
    
    /* Estilo original de tu tabla según capturas */
    #t-m { margin-top: 15px; border-radius: 5px; overflow: hidden; }
    #t-m th { background: #666; color: white; }
    
    .form-actions { display: flex; gap: 10px; margin-top: 10px; }
    .btn-fast-auth { background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px; transition: 0.3s; }
    .btn-fast-auth:hover { background-color: #218838; }
    .auth-active { background-color: #dc3545 !important; }
</style>

<div class="form-header">
    <h1>Generar Vale de Resguardo</h1>
</div>

<div class="card">
    <form action="guardar_reporte.php" method="POST" id="form-vale" onsubmit="return validarEnvio()">
        
        <div class="section-title">👤 Datos del Estudiante</div>
        <div class="form-row">
            <div class="input-group">
                <label>Estudiante (Buscar por nombre o cuenta)</label>
                <input list="lista-estudiantes" name="estudiante_display" id="input-estudiante" placeholder="Escriba para buscar..." required oninput="vincularEstudiante()" autocomplete="off">
                <input type="hidden" name="estudiante_id" id="estudiante_id_hidden" required>
            </div>
            <div class="input-group">
                <label>Nombre de la Práctica</label>
                <input type="text" name="practica" id="input-practica" placeholder="¿Qué actividad realizarán?">
            </div>
        </div>

        <div class="section-title">📍 Ubicación y Docente (Opcional)</div>
        <div class="form-row">
            <div class="input-group" style="grid-template-columns: 1fr 1fr; display: grid; gap: 10px;">
                <div>
                    <label>Mesa</label>
                    <input type="text" name="mesa" placeholder="Ej: 4">
                </div>
                <div>
                    <label>Máquina</label>
                    <input type="text" name="maquina" placeholder="Ej: Taladro">
                </div>
            </div>
            <div class="input-group">
                <label>Profesor de la Clase</label>
                <input type="text" name="nombre_profesor_opcional" id="input-profesor" placeholder="Nombre del docente titular">
            </div>
        </div>

        <div class="section-title">🔧 Lista de Materiales</div>
        <div class="material-box">
            <div style="display:flex; gap:10px;">
                <input list="lista-materiales" id="s-mat" style="flex:2;" placeholder="Buscar herramienta en inventario..." autocomplete="off">
                <input type="number" id="c-mat" value="1" min="1" style="width:80px;">
                <button type="button" class="btn btn-blue" onclick="addM_Mejorado()">+ Agregar</button>
            </div>
            
            <table id="t-m" style="display:none;">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th style="width:100px;">Cant.</th>
                        <th style="width:50px;"></th>
                    </tr>
                </thead>
                <tbody id="l-m"></tbody>
            </table>
            <input type="hidden" id="validar_materiales" value="0">
        </div>

        <div class="section-title" style="margin-top:25px;">🔑 Autorización</div>
        <div class="form-row">
            <div class="input-group">
                <label>Responsable que Entrega</label>
                <select name="responsable_salida_id" id="responsable_id" required>
                    <option value="">-- Seleccionar Encargado --</option>
                    <?php 
                    // Ya filtrado por activos
                    $res_r = mysqli_query($conexion, "SELECT * FROM responsables WHERE activo = 1 ORDER BY nombre ASC");
                    while($fr = mysqli_fetch_assoc($res_r)) echo "<option value='{$fr['id']}'>{$fr['nombre']}</option>";
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label>Firma Digital (Contraseña)</label>
                <input type="password" name="pass_responsable" id="pass_responsable" required placeholder="Ingrese su clave">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="flex: 2; padding:18px; font-size:1.1rem;">
                CONFIRMAR Y GENERAR VALE
            </button>
            <button type="button" id="btn-session" class="btn-fast-auth" style="flex: 1; padding:18px; font-size:0.9rem;" onclick="toggleSesionRapida()">
                AUTORIZACIÓN RÁPIDA (15 MIN)
            </button>
        </div>
    </form>
</div>

<datalist id="lista-estudiantes">
    <?php 
    // Los alumnos borrados ya no existen en esta tabla, por lo que no aparecerán aquí
    $res = mysqli_query($conexion, "SELECT * FROM estudiantes ORDER BY nombre ASC");
    while($f = mysqli_fetch_assoc($res)) echo "<option data-id='{$f['id']}' value='{$f['numero_cuenta']} - {$f['nombre']}'>";
    ?>
</datalist>

<datalist id="lista-materiales">
    <?php 
    $res_m = mysqli_query($conexion, "SELECT * FROM materiales ORDER BY nombre ASC");
    while($fm = mysqli_fetch_assoc($res_m)) echo "<option data-id='{$fm['id']}' value='{$fm['nombre']}'>";
    ?>
</datalist>

<script>
window.onload = function() { verificarSesion(); };

function vincularEstudiante() {
    const input = document.getElementById('input-estudiante');
    const hidden = document.getElementById('estudiante_id_hidden');
    const list = document.getElementById('lista-estudiantes');
    const option = Array.from(list.options).find(opt => opt.value === input.value);
    hidden.value = option ? option.dataset.id : "";
}

function addM_Mejorado() {
    const input = document.getElementById('s-mat');
    const canInput = document.getElementById('c-mat');
    const list = document.getElementById('lista-materiales');
    const option = Array.from(list.options).find(opt => opt.value === input.value);

    if(!option) {
        alert("Por favor, selecciona un material válido.");
        return;
    }

    const materialId = option.dataset.id;
    const nombreMaterial = input.value;
    const cantidadNueva = parseInt(canInput.value);

    const inputsExistentes = document.querySelectorAll('input[name="m_ids[]"]');
    let filaEncontrada = null;

    inputsExistentes.forEach(inp => {
        if(inp.value === materialId) {
            filaEncontrada = inp.closest('tr');
        }
    });

    if(filaEncontrada) {
        const inputCant = filaEncontrada.querySelector('input[name="m_cants[]"]');
        const nuevaSuma = parseInt(inputCant.value) + cantidadNueva;
        inputCant.value = nuevaSuma;
        filaEncontrada.cells[1].childNodes[0].nodeValue = nuevaSuma + " ";
    } else {
        const html = `<tr>
            <td>${nombreMaterial}<input type="hidden" name="m_ids[]" value="${materialId}"></td>
            <td>${cantidadNueva} <input type="hidden" name="m_cants[]" value="${cantidadNueva}"></td>
            <td><button type="button" onclick="eliminarFila(this)" style="color:red; border:none; background:none; cursor:pointer;">✖</button></td>
        </tr>`;
        document.getElementById('l-m').insertAdjacentHTML('beforeend', html);
    }

    document.getElementById('t-m').style.display = 'table';
    document.getElementById('validar_materiales').value = "1";
    input.value = ""; 
    canInput.value = "1";
}

function eliminarFila(btn) {
    btn.parentElement.parentElement.remove();
    if(document.querySelectorAll('#l-m tr').length === 0) {
        document.getElementById('t-m').style.display = 'none';
        document.getElementById('validar_materiales').value = "0";
    }
}

function toggleSesionRapida() {
    const sesion = localStorage.getItem('auth_sesion');
    if (sesion) {
        localStorage.removeItem('auth_sesion');
        alert("Sesión rápida desactivada.");
        location.reload();
    } else {
        const id = document.getElementById('responsable_id').value;
        const pass = document.getElementById('pass_responsable').value;
        if (!id || !pass) {
            alert("⚠️ Selecciona responsable e ingresa contraseña para activar.");
            return;
        }
        localStorage.setItem('auth_sesion', JSON.stringify({
            id_resp: id, pass_resp: pass, expira: new Date().getTime() + (15 * 60 * 1000)
        }));
        alert("✅ Autorización activa por 15 min.");
        verificarSesion();
    }
}

function verificarSesion() {
    const sesionStr = localStorage.getItem('auth_sesion');
    const btn = document.getElementById('btn-session');
    if (sesionStr) {
        const sesion = JSON.parse(sesionStr);
        if (new Date().getTime() > sesion.expira) {
            localStorage.removeItem('auth_sesion');
            btn.innerHTML = "AUTORIZACIÓN RÁPIDA (15 MIN)";
            btn.classList.remove('auth-active');
        } else {
            document.getElementById('responsable_id').value = sesion.id_resp;
            document.getElementById('pass_responsable').value = sesion.pass_resp;
            btn.innerHTML = "🔴 FINALIZAR AUTORIZACIÓN";
            btn.classList.add('auth-active');
        }
    }
}

function validarEnvio() {
    const hayMateriales = document.getElementById('validar_materiales').value;
    const estudianteId = document.getElementById('estudiante_id_hidden').value;
    const practica = document.getElementById('input-practica').value.trim();
    const profesor = document.getElementById('input-profesor').value.trim();

    verificarSesion();
    if(!estudianteId) { alert("⚠️ Selecciona un estudiante válido de la lista."); return false; }
    if(practica !== "" && profesor === "") {
        alert("⚠️ Si hay práctica, el nombre del Profesor es obligatorio.");
        document.getElementById('input-profesor').focus();
        return false;
    }
    if(hayMateriales === "0") { alert("⚠️ Agrega al menos un material."); return false; }
    return true;
}
</script>