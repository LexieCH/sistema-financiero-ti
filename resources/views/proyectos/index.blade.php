<x-app-layout>

@php
    // Mostramos "Nuevo proyecto" solo si el rol puede crear en este módulo.
    $usuarioAuth = Auth::user();
@endphp

<x-slot name="header">
    Proyectos
</x-slot>

<div class="page-header">
    <h2>Gestión de Proyectos</h2>
    <p>Proyectos registrados en la empresa</p>
</div>

<div class="card">

    <div class="dt-controls">

        <div class="card-title">
            Listado de proyectos
        </div>

        <div class="card-actions">

            @if($usuarioAuth->tienePermiso('proyectos', 'crear'))
            <a href="{{ route('proyectos.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nuevo proyecto
            </a>
            @endif

        </div>

    </div>


    <table id="tablaProyectos" style="width:100%">

        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            @foreach($proyectos as $p)

            <tr>

                <td class="id-cell">
                    #{{ str_pad($p->id,3,'0',STR_PAD_LEFT) }}
                </td>

                <td style="font-weight:600">
                    {{ $p->nombre }}
                </td>

                <td style="color:var(--text2)">
                    {{ $p->descripcion ?? '—' }}
                </td>

                <td style="display:flex; gap:6px;">
                    @if($usuarioAuth->tienePermiso('proyectos', 'editar'))
                    <a href="{{ route('proyectos.edit', $p) }}" class="btn-sm">Editar</a>
                    @endif

                    @if($usuarioAuth->tienePermiso('proyectos', 'eliminar'))
                    <form method="POST" action="{{ route('proyectos.destroy', $p) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-sm danger" onclick="return confirm('¿Desactivar proyecto?')">Desactivar</button>
                    </form>
                    @endif
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

<script>

$(document).ready(function(){

    $('#tablaProyectos').DataTable({

        pageLength:10,

        language:{
            paginate:{ previous:'←', next:'→' },
            info:'Mostrando _START_ – _END_ de _TOTAL_ registros',
            infoEmpty:'Sin registros',
            emptyTable:'No hay proyectos registrados',
        },

        dom:'tip'

    });

});

</script>

</x-app-layout>