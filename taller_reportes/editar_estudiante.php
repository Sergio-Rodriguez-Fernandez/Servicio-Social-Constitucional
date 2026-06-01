<?php
include("conexion.php");

// Verificamos si llega por ID o por Cuenta para evitar el error de "blanco"
$id_o_cuenta = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['cuenta_buscar']) ? $_GET['cuenta_buscar'] : null);

if (!$id_o_cuenta) {
    die("Error: No se proporcionó ID o Número de cuenta.");
}

$id_limpio = mysqli_real_escape_string($conexion, $id_o_cuenta);
// Buscamos ya sea por ID o por número de cuenta
$query = "SELECT * FROM estudiantes WHERE id = '$id_limpio' OR numero_cuenta = '$id_limpio' LIMIT 1";
$resultado = mysqli_query($conexion, $query);
$datos = mysqli_fetch_array($resultado);

if (!$datos) {
    echo "<script>alert('Estudiante no encontrado en la base de datos'); window.location.href='dashboard.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Estudiante</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="main-content" style="margin-left:0; padding:20px;">
        <div class="card" style="max-width:600px; margin:auto; background:white; padding:20px; border-radius:8px;">
            <h2>Editando: <?php echo htmlspecialchars($datos['nombre']); ?></h2>
            <form action="actualizar_estudiante.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
                
                <label>Nombre Completo</label>
                <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required style="width:100%; margin-bottom:15px; padding:8px;">
                
                <label>Número de Cuenta</label>
                <input type="text" name="cuenta" value="<?php echo $datos['numero_cuenta']; ?>" required style="width:100%; margin-bottom:15px; padding:8px;">
                
                <div style="display:flex; gap:10px;">
                    <div style="flex:1;">
                        <label>Grado</label>
                        <input type="text" name="grado" value="<?php echo $datos['grado']; ?>" style="width:100%; margin-bottom:15px; padding:8px;">
                    </div>
                    <div style="flex:1;">
                        <label>Grupo</label>
                        <input type="text" name="grupo" value="<?php echo $datos['grupo']; ?>" style="width:100%; margin-bottom:15px; padding:8px;">
                    </div>
                </div>
                
                <button type="submit" style="background-color: #ffc107; color: black; width:100%; padding:10px; border:none; cursor:pointer;">Actualizar Datos</button>
                <a href="dashboard.php" style="display:block; text-align:center; margin-top:15px; text-decoration:none; color: #666;">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>