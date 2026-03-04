<x-app-layout>


<x-slot name="header">
    Documentos
</x-slot>

{{-- KPI --}}
<div class="kpi-grid kpi-3">

    <div class="kpi-card">
        <div class="kpi-body">
            <div class="kpi-label">Monto pendiente</div>
            <div class="kpi-value yellow">
                ${{ number_format($totalPendiente,0,',','.') }}
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-body">
            <div class="kpi-label">Monto pagado</div>
            <div class="kpi-value green">
                ${{ number_format($totalPagado,0,',','.') }}
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-body">
            <div class="kpi-label">Total</div>
            <div class="kpi-value blue">
                ${{ number_format($totalGeneral,0,',','.') }}
            </div>
        </div>
    </div>

</div>


{{-- BOTON --}}
<div style="margin-bottom:20px">

    <button onclick="abrirModalDocumento()" class="btn-save">
        Nuevo documento
    </button>

</div>


{{-- TABLA --}}
<div class="card">

    <table id="tablaDocumentos">

        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Neto</th>
                <th>IVA</th>
                <th>Total</th>
                <th>Saldo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            @foreach($documentos as $doc)

                <tr>

                    <td>#{{ $doc->id }}</td>

                    <td>
                        {{ $doc->tipoDocumento->nombre ?? '-' }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($doc->fecha_emision)->format('d-m-Y') }}
                    </td>

                    <td>
                        ${{ number_format($doc->monto_neto,0,',','.') }}
                    </td>

                    <td>
                        ${{ number_format($doc->iva,0,',','.') }}
                    </td>

                    <td>
                        ${{ number_format($doc->total,0,',','.') }}
                    </td>

                    <td>
                        ${{ number_format($doc->saldoPendiente(),0,',','.') }}
                    </td>

                    <td>
                        {{ ucfirst($doc->estado) }}
                    </td>

                    <td>

                        @if($doc->estado == 'pendiente')

                        <button
                            class="btn-sm success"
                            onclick="abrirModalPago({{ $doc->id }})"
                        >
                            Registrar pago
                        </button>

                        @endif


                        <form
                            method="POST"
                            action="{{ route('documentos.destroy',$doc->id) }}"
                            style="display:inline"
                        >
                            @csrf
                            @method('DELETE')

                            <button class="btn-sm danger">
                                Eliminar
                            </button>

                        </form>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>


{{-- MODAL CREAR --}}
@include('documentos.modal-create')

@include('documentos.modal-pago')

{{-- FUTURO MODAL EDIT --}}

{{-- SCRIPTS --}}
@include('documentos.scripts')
```

</x-app-layout>
