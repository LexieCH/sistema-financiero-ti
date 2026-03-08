<x-app-layout>

@php
    // Acá controlamos qué botones mostrar según lo que permite el rol.
    $usuarioAuth = Auth::user();
@endphp

<x-slot name="header">
    Centros de Costos
</x-slot>

<x-slot name="topbarAction">
@if($usuarioAuth->tienePermiso('centros-costos', 'crear'))
<a href="{{ route('centros-costos.create') }}" class="btn-primary">
    <svg viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/>
        <line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Nuevo
</a>
@endif
</x-slot>


<div class="page-header">
    <h2>Centros de Costos</h2>
    <p>Gestión de centros de costos asociados a proyectos</p>
</div>


{{-- KPI --}}
<div class="kpi-grid kpi-3">

<div class="kpi-card">
    <div class="kpi-top">
        <div class="kpi-label">Total</div>
        <div class="kpi-icon blue">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
            </svg>
        </div>
    </div>

    <div class="kpi-body">
        <div class="kpi-value blue">{{ $total }}</div>
        <div class="kpi-sub">Centros registrados</div>
    </div>
</div>


<div class="kpi-card">
    <div class="kpi-top">
        <div class="kpi-label">Activos</div>
        <div class="kpi-icon green">
            <svg viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
    </div>

    <div class="kpi-body">
        <div class="kpi-value green">{{ $activos }}</div>
        <div class="kpi-sub">En funcionamiento</div>
    </div>
</div>


<div class="kpi-card">
    <div class="kpi-top">
        <div class="kpi-label">Inactivos</div>
        <div class="kpi-icon red">
            <svg viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </div>
    </div>

    <div class="kpi-body">
        <div class="kpi-value red">{{ $inactivos }}</div>
        <div class="kpi-sub">Deshabilitados</div>
    </div>
</div>

</div>



{{-- TABLA --}}
<div class="card">

<div class="card-header">
    <div class="card-title">Lista de Centros de Costo</div>

    <div class="card-actions">
        <div class="search-wrap">
            <svg viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>

            <input type="text" id="buscar" class="search-input" placeholder="Buscar...">
        </div>
    </div>
</div>


<table id="tablaCentros" class="display">

<thead>
<tr>
<th>ID</th>
<th>Centro de costo</th>
<th>Proyecto</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

@foreach($centros as $centro)

<tr>

<td class="id-cell">{{ $centro->id }}</td>

<td>
<strong>{{ $centro->nombre }}</strong>
</td>

<td>
{{ $centro->proyecto->nombre ?? 'Sin proyecto' }}
</td>

<td>

@if($centro->estado)
<span class="badge green">
<span class="badge-dot"></span>
Activo
</span>
@else
<span class="badge red">
<span class="badge-dot"></span>
Inactivo
</span>
@endif

</td>

<td>

@if($usuarioAuth->tienePermiso('centros-costos', 'editar'))
<a href="{{ route('centros-costos.edit',$centro) }}" class="btn-sm">
<svg viewBox="0 0 24 24">
<path d="M12 20h9"/>
<path d="M16.5 3.5l4 4L7 21H3v-4z"/>
</svg>
Editar
</a>
@endif

@if($usuarioAuth->tienePermiso('centros-costos', 'eliminar'))
<form action="{{ route('centros-costos.destroy',$centro) }}" method="POST" style="display:inline">
@csrf
@method('DELETE')

<button class="btn-sm danger" onclick="return confirm('¿Desactivar centro de costo?')">

<svg viewBox="0 0 24 24">
<polyline points="3 6 5 6 21 6"/>
<path d="M19 6l-2 14H7L5 6"/>
</svg>

Desactivar

</button>

</form>
@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</x-app-layout>
<script>

$(document).ready(function(){

    var tabla = $('#tablaCentros').DataTable({
        pageLength:10,
        language:{
            search:"",
            info:"Mostrando _START_ a _END_ de _TOTAL_ registros",
            paginate:{
                previous:"←",
                next:"→"
            }
        }
    });

    $('#buscar').on('keyup', function(){
        tabla.search(this.value).draw();
    });

});

</script>