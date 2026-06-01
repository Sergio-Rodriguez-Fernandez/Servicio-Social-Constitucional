<?php
// Incluimos la conexión (debe estar en la misma carpeta taller_reportes)
include("conexion.php");

// Verificamos que la solicitud sea por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibimos los datos del formulario de edición en tab_responsables.php
    // Usamos mysqli_real_escape_string para proteger contra inyecciones SQL
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $nueva_pass = mysqli_real_escape_string($conexion, $_POST['pass']); // La clave que se quiere poner
    $auth_pass = $_POST['auth_pass']; // La clave ACTUAL ingresada en el campo amarillo de validación

    // 1. Buscamos la contraseña que tiene el usuario actualmente en la Base de Datos
    $query_verificar = mysqli_query($conexion, "SELECT contrasena FROM responsables WHERE id = '$id'");
    
    if ($query_verificar && mysqli_num_rows($query_verificar) > 0) {
        $datos_db = mysqli_fetch_assoc($query_verificar);
        $password_actual_db = $datos_db['contrasena'];

        // 2. Comparamos la clave que el usuario escribió con la que está en la base de datos
        if ($password_actual_db === $auth_pass) {
            
            // Si la clave actual es correcta, ejecutamos la actualización
            $sql_update = "UPDATE responsables SET 
                           nombre = '$nombre', 
                           contrasena = '$nueva_pass' 
                           WHERE id = '$id'";

            if (mysqli_query($conexion, $sql_update)) {
                // Éxito: Usamos ruta absoluta para volver al dashboard
                echo "<script>
                        alert('Datos actualizados correctamente');
                        window.location.href='/taller_reportes/dashboard.php?tab=resp';
                      </script>";
            } else {
                // Error técnico en la consulta
                echo "Error al actualizar: " . mysqli_error($conexion);
            }

        } else {
            // Error: La contraseña actual no coincide
            echo "<script>
                    alert('Error: La contraseña actual ingresada es incorrecta. No se realizaron cambios.');
                    window.history.back();
                  </script>";
        }
    } else {
        // Error: No se encontró el ID del responsable
        echo "<script>
                alert('Error: El responsable no existe.');
                window.location.href='/taller_reportes/dashboard.php?tab=resp';
              </script>";
    }

} else {
    // Si intentan entrar al archivo directamente sin enviar el formulario
    header("Location: /taller_reportes/dashboard.php?tab=resp");
    exit();
}

// Cerramos la conexión al finalizar
mysqli_close($conexion);
?>