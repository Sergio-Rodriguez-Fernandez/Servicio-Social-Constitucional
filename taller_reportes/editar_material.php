<?php
include("conexion.php");

if(isset($_GET['id_buscar'])){
    $id = mysqli_real_escape_string($conexion, $_GET['id_buscar']);
    $query = "SELECT * FROM materiales WHERE id = $id";
    $res = mysqli_query($conexion, $query);
    $datos = mysqli_fetch_assoc($res);

    if(!$datos){
        echo "<script>alert('Material no encontrado'); window.location.href='dashboard.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Material</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="main-content" style="margin-left:0; padding:20px;">
        <div class="card" style="max-width:500px; margin:auto;">
            <h1>Editar Material</h1>
            <form action="actualizar_material.php" method="POST" class="form-grid">
                <input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
                
                <div class="form-group full-width">
                    <label>Nombre de la Herramienta / Material</label>
                    <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required>
                </div>

                <button type="submit" style="background-color: #ffc107; color: black;">Actualizar Nombre</button>
                <a href="dashboard.php" style="text-align:center; display:block; margin-top:10px; color:#666;">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>