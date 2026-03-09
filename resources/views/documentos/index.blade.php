<x-app-layout>


<x-slot name="header">
    Documentos
</x-slot>

@php
    // Usamos permisos por acción para mostrar solo botones válidos para cada rol.
    $usuarioAuth = Auth::user();
@endphp

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
    @if($usuarioAuth->tienePermiso('documentos', 'crear'))
    <button onclick="abrirModalDocumento()" class="btn-save">
        Nuevo documento
    </button>
    @endif

</div>


{{-- TABLA --}}
<div class="card">

    <table id="tablaDocumentos">

        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Tercero</th>
                <th>Fecha</th>
                <th>Neto</th>
                <th>IVA</th>
                <th>Total</th>
                <th>Saldo</th>
                <th>PDF</th>
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
                        @if($doc->tercero)
                            {{ $doc->tercero->rut ?? 'Sin RUT' }} - {{ $doc->tercero->razon_social }}
                        @else
                            —
                        @endif
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
                        @if($doc->pdf_url)
                            <a href="{{ asset('storage/' . $doc->pdf_url) }}" target="_blank" class="btn-sm">
                                Ver PDF
                            </a>
                        @else
                            <span class="badge yellow"><span class="badge-dot"></span>Sin PDF</span>
                        @endif
                    </td>

                    <td>
                        {{ ucfirst($doc->estado) }}
                    </td>

                    <td>

                        @if($doc->estado == 'pendiente' && $usuarioAuth->tienePermiso('pagos', 'crear'))

                        <button
                            class="btn-sm success"
                            onclick="abrirModalPago({{ $doc->id }}, {{ (float) $doc->saldoPendiente() }})"
                        >
                            Registrar pago
                        </button>

                        @endif

                        @if($usuarioAuth->tienePermiso('documentos', 'eliminar'))
                        <form
                            method="POST"
                            action="{{ route('documentos.destroy',$doc->id) }}"
                            style="display:inline"
                        >
                            @csrf
                            @method('DELETE')

                            <button class="btn-sm danger" onclick="return confirm('¿Anular documento?')">
                                Anular
                            </button>

                        </form>
                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>


{{-- MODAL CREAR --}}
@if($usuarioAuth->tienePermiso('documentos', 'crear'))
@include('documentos.modal-create')
@endif

@if($usuarioAuth->tienePermiso('pagos', 'crear'))
@include('documentos.modal-pago')
@endif

{{-- FUTURO MODAL EDIT --}}

{{-- SCRIPTS --}}
@include('documentos.scripts')
```

</x-app-layout>
