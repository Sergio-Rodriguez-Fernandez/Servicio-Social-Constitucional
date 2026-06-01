<script>
function showTab(id) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sidebar nav a').forEach(a => a.classList.remove('active'));
    const targetTab = document.getElementById(id);
    if(targetTab) targetTab.classList.add('active');
    const targetLink = document.getElementById('link-' + id);
    if(targetLink) targetLink.classList.add('active');
    localStorage.setItem('tabActiva', id);
}

// Función Genérica para mostrar/ocultar formularios (Nuevo y Editar)
function toggleElement(id) {
    const el = document.getElementById(id);
    if(el.style.display === "none" || el.style.display === "") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}

function filterTable(inputId, tableId) {
    let filter = document.getElementById(inputId).value.toUpperCase();
    let trs = document.getElementById(tableId).getElementsByTagName("tr");
    for (let i = 1; i < trs.length; i++) {
        let txt = trs[i].textContent || trs[i].innerText;
        trs[i].style.display = txt.toUpperCase().indexOf(filter) > -1 ? "" : "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    let tab = localStorage.getItem('tabActiva') || 'nueva';
    showTab(tab);
});
</script>

<style>
    /* Botones */
    .btn-red { background: #dc3545 !important; color: white !important; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.85rem;}
    .btn-blue { background: #007bff !important; color: white !important; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none; }
    .btn-orange { background: #fd7e14 !important; color: white !important; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.85rem;}
    .btn-primary { background: #28a745 !important; color: white !important; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
    
    /* Tablas Estilo Imagen */
    table.tabla-estilo { width: 100%; border-collapse: collapse; background: white; }
    table.tabla-estilo thead tr { background: #333; color: white; }
    table.tabla-estilo th { padding: 12px; text-align: left; font-weight: 500; }
    table.tabla-estilo td { padding: 10px 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
    
    .form-edit { background: #fdfdfd; padding: 15px; border: 1px solid #ddd; margin: 10px 0; border-radius: 5px; }
</style>