<?php
include("conexion.php");
if(isset($_GET['id_buscar'])){
    $id = $_GET['id_buscar'];
    $query = "SELECT * FROM responsables WHERE id = $id";
    $res = mysqli_query($conexion, $query);
    $datos = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><link rel="stylesheet" href="estilos.css"></head>
<body>
    <div class="card" style="max-width:500px; margin:50px auto; padding:20px;">
        <h1>Editar Responsable</h1>
        <form action="actualizar_responsable.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required>
            <label>Contraseña</label>
            <input type="text" name="pass" value="<?php echo $datos['contrasena']; ?>" required>
            <button type="submit" class="btn-guardar" style="background:var(--yellow); color:black;">Actualizar</button>
            <a href="dashboard.php">Cancelar</a>
        </form>
    </div>
</body>
</html>