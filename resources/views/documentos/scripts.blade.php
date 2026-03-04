<script>

function abrirModalDocumento(){
    document.getElementById("modalDocumento").style.display = "block";
}

function cerrarModalDocumento(){

    var modal = document.getElementById("modalDocumento");

    modal.style.display = "none";

    // limpiar formulario
    document.querySelector("#modalDocumento form").reset();

    // limpiar calculos
    document.getElementById('iva_display').value =
    '$' + iva.toLocaleString('es-CL');

    document.getElementById('total_display').value =
        '$' + total.toLocaleString('es-CL');

}


// cerrar modal si se hace click fuera
window.onclick = function(event){

    var modal = document.getElementById("modalDocumento");

    if(event.target == modal){
        modal.style.display = "none";
    }

}


// calcular IVA
document.getElementById('monto_neto').addEventListener('input', function(){

    var neto = parseFloat(this.value) || 0;

    var iva = Math.round(neto * 0.19);

    var total = neto + iva;

    document.getElementById('iva_display').value = iva;

    document.getElementById('total_display').value = total;

});


$(document).ready(function(){

    $('#tablaDocumentos').DataTable();

});

//modal pago
function abrirModalPago(id){

    document.getElementById("modalPago").style.display="block";

    document.getElementById("pago_documento_id").value=id;

    }

    function cerrarModalPago(){

    document.getElementById("modalPago").style.display="none";

}

</script>
