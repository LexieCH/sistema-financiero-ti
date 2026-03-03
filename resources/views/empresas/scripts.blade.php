<script>
$(document).ready(function() {
    $('#tablaEmpresas').DataTable({
        pageLength: 5,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});

function abrirModal(){
    document.getElementById('modalEmpresa').classList.remove('hidden');
    document.getElementById('modalEmpresa').classList.add('flex');
}

function cerrarModal(){
    document.getElementById('modalEmpresa').classList.add('hidden');
}
</script>