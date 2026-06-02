<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h1>Responsables de Taller</h1>
    <button class="btn btn-blue" onclick="toggleElement('form-nuevo-resp')">➕ Nuevo Responsable</button>
</div>

<div id="form-nuevo-resp" class="card" style="display:none; margin-bottom:20px; border-left: 5px solid #007bff;">
    <h3 style="margin-top:0;">Registrar Nuevo Acceso</h3>
    <form action="/taller_reportes/guardar_responsable.php" method="POST" style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
        <input type="text" name="nombre" placeholder="Nombre del responsable" required style="padding:10px; border: 1px solid #ccc; border-radius: 4px;">
        <input type="password" name="contrasena" placeholder="Asignar Contraseña" required style="padding:10px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" class="btn btn-primary" style="grid-column: span 2; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Crear Responsable</button>
    </form>
</div>

<div class="card" style="padding:0;"> 
    <div style="padding:10px; background:#f8f9fa; border-bottom: 1px solid #eee;">
        <input type="text" id="bus-resp" onkeyup="filterTable('bus-resp', 'tabla-responsables')" placeholder="Buscar responsable..." style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
    </div>

    <table class="tabla-estilo" id="tabla-responsables" style="width:100%;">
        <thead>
            <tr>
                <th>Nombre del Responsable</th>
                <th>Estado</th>
                <th style="text-align:right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Mostramos todos para poder reactivarlos si es necesario
            $res = mysqli_query($conexion, "SELECT * FROM responsables ORDER BY activo DESC, nombre ASC");
            while($r = mysqli_fetch_assoc($res)): 
                $estaActivo = $r['activo'] == 1;
            ?>
            <tr style="<?php echo !$estaActivo ? 'opacity: 0.6; background: #f9f9f9;' : ''; ?>">
                <td style="padding:15px;">
                    <strong><?php echo htmlspecialchars($r['nombre']); ?></strong>
                </td>
                <td style="padding:15px;">
                    <?php echo $estaActivo ? '<span style="color:green;">● Activo</span>' : '<span style="color:red;">○ Inactivo</span>'; ?>
                </td>
                <td style="text-align:right; padding:15px; position: relative;">
                    <button class="btn-orange" onclick="toggleElement('edit-resp-<?php echo $r['id']; ?>')" style="background-color: #fd7e14; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-right: 5px;">
                        Modificar
                    </button>
                    
                    <?php if($estaActivo): ?>
                    <button class="btn-red" onclick="toggleElement('confirm-del-<?php echo $r['id']; ?>')" style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                       Dar de Baja
                    </button>
                    <?php else: ?>
                    <a href="cambiar_estado_resp.php?id=<?php echo $r['id']; ?>&estado=1" class="btn-blue" style="background-color: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration:none; font-size:12px;">
                       Reactivar
                    </a>
                    <?php endif; ?>

                    <div id="confirm-del-<?php echo $r['id']; ?>" style="display:none; position:absolute; right:10px; top:40px; background:white; border:1px solid red; padding:12px; z-index:9999; border-radius:8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); width: 160px; text-align: left;">
                        <form action="cambiar_estado_resp.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                            <input type="hidden" name="estado" value="0">
                            <p style="margin:0 0 8px 0; font-size:11px; color:red; font-weight:bold; line-height:1.2;">Clave de responsable para dar de baja:</p>
                            <input type="password" name="auth_pass" placeholder="Clave..." required style="padding:6px; width:100%; margin-bottom:8px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box;">
                            <div style="display:flex; gap:5px;">
                                <button type="submit" style="background:red; color:white; border:none; padding:6px; border-radius:4px; cursor:pointer; font-size:10px; flex:2; font-weight:bold;">CONFIRMAR</button>
                                <button type="button" onclick="toggleElement('confirm-del-<?php echo $r['id']; ?>')" style="background:#666; color:white; border:none; padding:6px; border-radius:4px; cursor:pointer; font-size:10px; flex:1;">X</button>
                            </div>
                        </form>
                    </div>

                    <div id="edit-resp-<?php echo $r['id']; ?>" class="form-edit" style="display:none; text-align:left; margin-top:15px; padding:15px; background:#fff; border:1px solid #ddd; border-radius:8px;">
                        <form action="/taller_reportes/actualizar_responsable.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                            <label style="display:block; font-weight:bold; margin-bottom:5px; font-size:0.9rem;">Nombre:</label>
                            <input type="text" name="nombre" value="<?php echo $r['nombre']; ?>" required style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;">
                            <label style="display:block; font-weight:bold; margin-bottom:5px; font-size:0.9rem;">Nueva Contraseña:</label>
                            <input type="password" name="pass" value="<?php echo $r['contrasena']; ?>" required style="width:100%; margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;">
                            <div style="background: #fff3cd; padding: 10px; border-radius: 4px; margin-bottom: 10px; border: 1px solid #ffeeba;">
                                <label style="display:block; font-weight:bold; color: #856404; font-size:0.8rem;">Contraseña ACTUAL para guardar:</label>
                                <input type="password" name="auth_pass" required style="width:100%; padding:8px; border:1px solid #ffeeba; box-sizing:border-box;">
                            </div>
                            <div style="display:flex; gap:10px;">
                                <button type="submit" class="btn btn-primary" style="flex:1;">Actualizar</button>
                                <button type="button" class="btn" onclick="toggleElement('edit-resp-<?php echo $r['id']; ?>')" style="flex:1; background:#6c757d; color:white; border:none; border-radius:4px;">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>