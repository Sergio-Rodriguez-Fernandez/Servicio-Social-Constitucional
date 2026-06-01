<?php 
include("conexion.php"); 
date_default_timezone_set('America/Mexico_City');

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d', strtotime('-7 days'));
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Taller FIME</title>
    <link rel="stylesheet" href="estilos.css">
    
    <style>
        /* ESTILOS CRÍTICOS PARA REPARAR LA ESTRUCTURA */
        body {
            margin: 0;
            padding: 0;
            display: flex; /* Esto obliga a que sidebar y contenido estén alineados */
            background-color: #f4f7f6;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed; /* La mantiene pegada a la izquierda */
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .main-content {
            flex: 1; /* Ocupa el resto del espacio */
            margin-left: 250px; /* EMPUJA el contenido a la derecha de la sidebar */
            padding: 30px;
            box-sizing: border-box;
            width: calc(100% - 250px);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php include("includes/sidebar.php"); ?>

    <div class="main-content">
        <div id="nueva" class="tab-content"><?php include("includes/tab_nueva_practica.php"); ?></div>
        <div id="curso" class="tab-content"><?php include("includes/tab_en_curso.php"); ?></div>
        <div id="historial" class="tab-content"><?php include("includes/tab_historial.php"); ?></div>
        <div id="estudiantes" class="tab-content"><?php include("includes/tab_estudiantes.php"); ?></div>
        <div id="materiales" class="tab-content"><?php include("includes/tab_materiales.php"); ?></div>
        <div id="resp" class="tab-content"><?php include("includes/tab_responsables.php"); ?></div>
    </div>

    <?php include("includes/scripts.php"); ?>
</body>
</html>