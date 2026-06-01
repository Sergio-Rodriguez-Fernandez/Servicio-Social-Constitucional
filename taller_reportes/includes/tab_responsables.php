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

<div class="card" style="padding:0; overflow:hidden;">
    <div style="padding:10px; background:#f8f9fa; border-bottom: 1px solid #eee;">
        <input type="text" id="bus-resp" onkeyup="filterTable('bus-resp', 'tabla-responsables')" placeholder="Buscar responsable..." style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
    </div>

    <table class="tabla-estilo" id="tabla-responsables">
        <thead>
            <tr>
                <th>Nombre del Responsable</th>
                <th style="text-align:right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $res = mysqli_query($conexion, "SELECT * FROM responsables ORDER BY nombre ASC");
            while($r = mysqli_fetch_assoc($res)): 
            ?>
            <tr>
                <td><?php echo htmlspecialchars($r['nombre']); ?></td>
                <td style="text-align:right;">
                    <button class="btn-orange" onclick="toggleElement('edit-resp-<?php echo $r['id']; ?>')" style="background-color: #fd7e14; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-right: 5px;">
                        Modificar
                    </button>
                    
                    <button class="btn-red" onclick="toggleElement('confirm-del-<?php echo $r['id']; ?>')" style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 13.33px;">
                       Eliminar
                    </button>

                    <div id="confirm-del-<?php echo $r['id']; ?>" style="display:none; position:absolute; right:10px; background:white; border:1px solid red; padding:10px; z-index:100; border-radius:8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-top:5px;">
                        <form action="/taller_reportes/eliminar.php" method="GET">
                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                            <input type="hidden" name="tipo" value="responsable">
                            <p style="margin:0 0 5px 0; font-size:11px; color:red;">Confirma con tu clave:</p>
                            <input type="password" name="auth" placeholder="Contraseña actual" required style="padding:4px; width:120px; margin-bottom:5px;">
                            <br>
                            <button type="submit" style="background:red; color:white; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:11px;">Confirmar Borrado</button>
                            <button type="button" onclick="toggleElement('confirm-del-<?php echo $r['id']; ?>')" style="background:#ccc; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:11px;">X</button>
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
                                <label style="display:block; font-weight:bold; color: #856404; font-size:0.8rem;">Para guardar cambios, ingresa la contraseña ACTUAL:</label>
                                <input type="password" name="auth_pass" required style="width:100%; padding:8px; border:1px solid #ffeeba;">
                            </div>

                            <div style="display:flex; gap:10px;">
                                <button type="submit" class="btn btn-primary" style="flex:1; background-color: #007bff; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer;">Actualizar</button>
                                <button type="button" class="btn" onclick="toggleElement('edit-resp-<?php echo $r['id']; ?>')" style="flex:1; background-color: #6c757d; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer;">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>