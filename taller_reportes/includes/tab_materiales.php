<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h1>Listado de Materiales</h1>
    <button class="btn btn-blue" onclick="toggleElement('form-nuevo-mat')">➕ Nuevo Material</button>
</div>

<div id="form-nuevo-mat" class="card" style="display:none; margin-bottom:20px; border-left: 5px solid #007bff;">
    <form action="/taller_reportes/guardar_material.php" method="POST" style="display:flex; gap:10px;">
        <input type="text" name="nombre" placeholder="Nombre de la herramienta o material" required style="flex:2; padding:10px;">
        <button type="submit" class="btn btn-primary" style="flex:1;">Guardar en Lista</button>
    </form>
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <table id="tab-m" class="tabla-estilo">
        <thead>
            <tr>
                <th>Nombre del Material</th>
                <th style="text-align:right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $res = mysqli_query($conexion, "SELECT * FROM materiales ORDER BY nombre ASC");
            while($m = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><b><?php echo $m['nombre']; ?></b></td>
                <td style="text-align:right;">
                    <button class="btn-orange" onclick="toggleElement('edit-mat-<?php echo $m['id']; ?>')">Modificar</button>
                    <a href="/taller_reportes/eliminar.php?id=<?php echo $m['id']; ?>&tipo=material" class="btn-red" onclick="return confirm('¿Borrar?')">Eliminar</a>
                    
                    <div id="edit-mat-<?php echo $m['id']; ?>" class="form-edit" style="display:none; text-align:left;">
                        <form action="/taller_reportes/actualizar_material.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                            <input type="text" name="nombre" value="<?php echo $m['nombre']; ?>" style="width:70%;">
                            <button type="submit" class="btn btn-primary">Ok</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>