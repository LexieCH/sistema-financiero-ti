<x-app-layout>

<x-slot name="header">
    Detalle del tercero
</x-slot>

<div class="page-header">
    <h2>{{ $tercero->razon_social }}</h2>
    <p>Información completa del tercero</p>
</div>


{{-- HERO --}}
<div class="card" style="margin-bottom:20px">

    <div class="card-header">

        <div style="display:flex;align-items:center;gap:14px">

            <div class="avatar">
                {{ strtoupper(substr($tercero->razon_social,0,2)) }}
            </div>

            <div>
                <div style="font-weight:700;font-size:16px">
                    {{ $tercero->razon_social }}
                </div>

                <div style="font-size:12px;color:var(--muted)">

                    <span style="font-family:'JetBrains Mono',monospace">
                        {{ $tercero->rut }}
                    </span>

                    • 

                    @if($tercero->tipo === 'cliente')
                        <span class="badge blue"><span class="badge-dot"></span>Cliente</span>
                    @else
                        <span class="badge yellow"><span class="badge-dot"></span>Proveedor</span>
                    @endif

                    •

                    <span class="badge green">
                        <span class="badge-dot"></span>Activo
                    </span>

                </div>
            </div>

        </div>

        <div class="card-actions">

            <a href="{{ route('terceros.index') }}" class="btn-sm">
                Volver
            </a>

        </div>

    </div>

</div>


{{-- STATS --}}
<div class="kpi-grid kpi-3">

    <div class="kpi-card">
        <div class="kpi-label">Documentos</div>
        <div class="kpi-value blue">
            {{ $documentos->count() }}
        </div>
        <div class="kpi-sub">Registrados</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Total facturado</div>
        <div class="kpi-value green">
            ${{ number_format($totalFacturado,0,',','.') }}
        </div>
        <div class="kpi-sub">CLP histórico</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Movimientos</div>
        <div class="kpi-value blue">
            {{ $movimientos->count() }}
        </div>
        <div class="kpi-sub">Registrados</div>
    </div>

</div>



{{-- INFO GRID --}}
<div class="kpi-grid kpi-2" style="margin-top:20px">


{{-- IDENTIFICACION --}}
<div class="card">

<div class="card-header">
<div class="card-title">Identificación</div>
</div>

<div class="form-grid">

<div class="form-group">
<label>RUT</label>
<div class="form-control">
{{ $tercero->rut }}
</div>
</div>

<div class="form-group">
<label>Tipo</label>
<div class="form-control">
{{ ucfirst($tercero->tipo) }}
</div>
</div>

<div class="form-group form-col-2">
<label>Razón social</label>
<div class="form-control">
{{ $tercero->razon_social }}
</div>
</div>

</div>

</div>



{{-- CONTACTO --}}
<div class="card">

<div class="card-header">
<div class="card-title">Contacto</div>
</div>

<div class="form-grid">

<div class="form-group">
<label>Teléfono</label>
<div class="form-control">
{{ $tercero->telefono ?? '—' }}
</div>
</div>

<div class="form-group">
<label>Email</label>
<div class="form-control">
{{ $tercero->email ?? '—' }}
</div>
</div>

<div class="form-group form-col-2">
<label>Dirección</label>
<div class="form-control">
{{ $tercero->direccion ?? '—' }}
</div>
</div>

</div>

</div>



{{-- DATOS BANCARIOS --}}
<div class="card" style="grid-column:1 / -1">

<div class="card-header">
<div class="card-title">Datos bancarios</div>
</div>

<div class="form-grid">

<div class="form-group">
<label>Banco</label>
<div class="form-control">
{{ $tercero->banco ?? '—' }}
</div>
</div>

<div class="form-group">
<label>Tipo de cuenta</label>
<div class="form-control">
{{ $tercero->tipo_cuenta ? ucfirst(str_replace('_',' ',$tercero->tipo_cuenta)) : '—' }}
</div>
</div>

<div class="form-group">
<label>Número de cuenta</label>
<div class="form-control">
{{ $tercero->numero_cuenta ?? '—' }}
</div>
</div>

</div>

</div>

</div>



{{-- HISTORIAL --}}
<div class="card" style="margin-top:20px">

<div class="card-header">
<div class="card-title">Últimos movimientos</div>
</div>

<table class="dataTable no-footer">

<thead>

<tr>
<th>ID</th>
<th>Fecha</th>
<th>Monto</th>
<th>Descripción</th>
</tr>

</thead>

<tbody>

@foreach($movimientos as $mov)

<tr>

<td class="id-cell">
{{ str_pad($mov->id,3,'0',STR_PAD_LEFT) }}
</td>

<td>
{{ \Carbon\Carbon::parse($mov->fecha)->format('d-m-Y') }}
</td>

<td class="amount {{ $mov->monto >= 0 ? 'pos':'neg' }}">
${{ number_format($mov->monto,0,',','.') }}
</td>

<td>
{{ $mov->descripcion }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>


</x-app-layout>