<style>
    :root {
        --primary: #28a745;
        --dark: #333;
        --blue: #007bff;
    }

    body {
        margin: 0;
        display: flex; /* Esto pone el sidebar a la izquierda y el contenido a la derecha */
        background: #f4f4f4;
        font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background: var(--dark);
        color: white;
        position: fixed; /* Esto la deja fija aunque hagas scroll */
        left: 0;
        top: 0;
        padding: 20px;
        box-sizing: border-box;
    }

    .main-content {
        margin-left: 250px; /* IMPORTANTE: Debe ser igual al ancho de la sidebar */
        flex: 1;
        padding: 30px;
        min-height: 100vh;
    }

    .tab-content {
        display: none; /* Se ocultan todas por defecto */
    }

    .tab-content.active {
        display: block; /* Solo se muestra la pestaña activa */
    }
</style>