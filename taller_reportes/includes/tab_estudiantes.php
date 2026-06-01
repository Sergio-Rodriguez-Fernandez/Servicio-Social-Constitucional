<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h1>Estudiantes Registrados</h1>
    <button class="btn btn-blue" onclick="toggleElement('form-nuevo-est')">➕ Nuevo Estudiante</button>
</div>

<div id="form-nuevo-est" class="card" style="display:none; margin-bottom:20px; border-left: 5px solid #007bff;">
    <form action="/taller_reportes/guardar_estudiante.php" method="POST" style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px;">
        <input type="text" name="nombre" placeholder="Nombre completo" required style="padding:10px; border: 1px solid #ccc; border-radius: 4px;">
        <input type="text" name="cuenta" placeholder="No. Cuenta" required style="padding:10px; border: 1px solid #ccc; border-radius: 4px;">
        <div style="display:flex; gap:5px;">
            <input type="text" name="grado" placeholder="Grado" required style="width:50%; padding:10px; border: 1px solid #ccc; border-radius: 4px;">
            <input type="text" name="grupo" placeholder="Grupo" required style="width:50%; padding:10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <button type="submit" class="btn btn-primary" style="grid-column: span 3; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Registrar Estudiante</button>
    </form>
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <div style="padding:10px; background:#f8f9fa; border-bottom: 1px solid #eee;">
        <input type="text" id="bus-e" onkeyup="filterTable('bus-e', 'tabla-estudiantes')" placeholder="Buscar estudiante..." style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
    </div>

    <table class="tabla-estilo" id="tabla-estudiantes">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>No. Cuenta</th>
                <th>Grado/Grupo</th>
                <th style="text-align:right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $res = mysqli_query($conexion, "SELECT * FROM estudiantes ORDER BY nombre ASC");
            while($e = mysqli_fetch_assoc($res)): 
            ?>
            <tr>
                <td><?php echo htmlspecialchars($e['nombre']); ?></td>
                <td><?php echo htmlspecialchars($e['numero_cuenta']); ?></td>
                <td><?php echo $e['grado'] . "° " . $e['grupo']; ?></td>
                <td style="text-align:right;">
                    <button class="btn-orange" onclick="toggleElement('edit-est-<?php echo $e['id']; ?>')" style="background-color: #fd7e14; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; margin-right: 5px;">Modificar</button>
                    
                    <a href="/taller_reportes/eliminar.php?id=<?php echo $e['id']; ?>&tipo=estudiante" 
                       class="btn-red" 
                       onclick="return confirm('¿Borrar estudiante?')" 
                       style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 13px;">
                       Eliminar
                    </a>
                    
                    <div id="edit-est-<?php echo $e['id']; ?>" class="form-edit" style="display:none; text-align:left; margin-top:10px; padding:10px; border:1px solid #ddd; border-radius:8px;">
                        <form action="/taller_reportes/actualizar_estudiante.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $e['id']; ?>">
                            <input type="text" name="nombre" value="<?php echo $e['nombre']; ?>" style="width:100%; margin-bottom:5px; padding:8px;">
                            <input type="text" name="cuenta" value="<?php echo $e['numero_cuenta']; ?>" style="width:100%; margin-bottom:5px; padding:8px;">
                            <div style="display:flex; gap:5px; margin-bottom:5px;">
                                <input type="text" name="grado" value="<?php echo $e['grado']; ?>" placeholder="Grado" style="width:50%; padding:8px;">
                                <input type="text" name="grupo" value="<?php echo $e['grupo']; ?>" placeholder="Grupo" style="width:50%; padding:8px;">
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%; padding:8px; background:#007bff; color:white; border:none; border-radius:4px;">Actualizar</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>