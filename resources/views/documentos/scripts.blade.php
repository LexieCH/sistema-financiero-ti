<script>

function abrirModalDocumento(){
    document.getElementById("modalDocumento").classList.add('open');
}

function cerrarModalDocumento(){

    var modal = document.getElementById("modalDocumento");

    modal.classList.remove('open');

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
    var modalPago = document.getElementById("modalPago");

    if(event.target == modal){
        modal.classList.remove('open');
    }

    if(event.target == modalPago){
        modalPago.classList.remove('open');
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
let saldoPendientePagoActual = 0;

function fechaMasDias(dias) {
    const fecha = new Date();
    fecha.setDate(fecha.getDate() + dias);
    return fecha.toISOString().slice(0, 10);
}

function generarPlanCuotas(montoTotal, periodos) {
    const plan = [];

    if (periodos <= 0) {
        return plan;
    }

    const cuotaBase = Math.round((montoTotal / periodos) * 100) / 100;
    let acumulado = 0;

    for (let i = 1; i <= periodos; i++) {
        let montoCuota = cuotaBase;

        if (i === periodos) {
            montoCuota = Math.round((montoTotal - acumulado) * 100) / 100;
        }

        acumulado += montoCuota;

        plan.push({
            numero: i,
            fecha: fechaMasDias(i * 30),
            monto: montoCuota,
        });
    }

    return plan;
}

function renderPlanCuotas(plan) {
    const contenedor = document.getElementById('pago_plan_container');
    const cuerpo = document.getElementById('pago_plan_body');
    const texto = document.getElementById('pago_plan_texto');

    if (!contenedor || !cuerpo || !texto) {
        return;
    }

    if (!Array.isArray(plan) || plan.length <= 1) {
        contenedor.style.display = 'none';
        cuerpo.innerHTML = '';
        texto.textContent = '';
        return;
    }

    contenedor.style.display = 'block';
    cuerpo.innerHTML = '';

    plan.forEach(function (cuota) {
        const fila = document.createElement('tr');
        fila.innerHTML =
            '<td style="padding:10px 12px;border-bottom:1px solid #2a2e42;color:#f0f2fa;">' + cuota.numero + '</td>' +
            '<td style="padding:10px 12px;border-bottom:1px solid #2a2e42;color:#f0f2fa;">' + cuota.fecha + '</td>' +
            '<td style="padding:10px 12px;text-align:right;border-bottom:1px solid #2a2e42;color:#f0f2fa;">$' + cuota.monto.toLocaleString('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
        cuerpo.appendChild(fila);
    });

    texto.textContent = 'El monto se divide en cuotas iguales cada 30 días.';
}

function aplicarCondicionPago() {
    const condicion = document.getElementById('pago_condicion');
    const fecha = document.getElementById('pago_fecha');
    const monto = document.getElementById('pago_monto');

    if (!condicion || !fecha || !monto) {
        return;
    }

    const dias = condicion.value === 'total' ? 0 : parseInt(condicion.value, 10);
    const diasNormalizados = isNaN(dias) ? 0 : dias;
    const periodos = diasNormalizados <= 0 ? 1 : Math.max(1, Math.floor(diasNormalizados / 30));
    const plan = generarPlanCuotas(saldoPendientePagoActual, periodos);

    if (plan.length > 0) {
        fecha.value = plan[0].fecha;
        monto.value = plan[0].monto;
    } else {
        fecha.value = fechaMasDias(diasNormalizados);
        monto.value = saldoPendientePagoActual;
    }

    monto.readOnly = true;

    renderPlanCuotas(plan);
}

function abrirModalPago(id, saldoPendiente = 0){

    document.getElementById("modalPago").classList.add('open');

    document.getElementById("pago_documento_id").value=id;

    saldoPendientePagoActual = parseFloat(saldoPendiente) || 0;
    aplicarCondicionPago();

    }

    function cerrarModalPago(){

    document.getElementById("modalPago").classList.remove('open');

    const formPago = document.querySelector('#modalPago form');

    if (formPago) {
        formPago.reset();
    }

    saldoPendientePagoActual = 0;
    renderPlanCuotas([]);

}

document.addEventListener('DOMContentLoaded', function () {
    const condicion = document.getElementById('pago_condicion');

    if (condicion) {
        condicion.addEventListener('change', aplicarCondicionPago);
    }
});

</script>
