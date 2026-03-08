<x-app-layout>

<x-slot name="header">
    Bitácora del Sistema
</x-slot>

    <div class="page-header">
        <h2>Bitácora del Sistema</h2>
        <p>Registro de todas las acciones realizadas en el sistema</p>
    </div>

    <div class="card">

        <div class="card-header">
            <span class="card-title">Listado general</span>
            <div class="card-actions">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="searchInput" class="search-input" placeholder="Buscar en bitácora...">
                </div>
            </div>
        </div>

        <table id="tablaBitacora" class="dataTable no-footer" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bitacoras as $i => $b)
                <tr>

                    <td><span class="id-cell">{{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}</span></td>

                    <td><span class="id-cell">{{ $b->created_at->format('d-m-Y H:i') }}</span></td>

                    <td>
                        @php $initials = strtoupper(substr($b->usuario->name ?? 'NA', 0, 2)); @endphp
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="avatar" style="width:30px;height:30px;font-size:10px;">{{ $initials }}</div>
                            <span style="font-weight:500;">{{ $b->usuario->name ?? '—' }}</span>
                        </div>
                    </td>

                    <td>
                        <span class="badge blue">
                            <span class="badge-dot"></span>
                            {{ $b->modulo }}
                        </span>
                    </td>

                    <td>
                        @php
                            $colorMap = [
                                'crear'    => 'green',
                                'editar'   => 'yellow',
                                'eliminar' => 'red',
                                'ver'      => 'blue',
                            ];
                            $color = $colorMap[strtolower($b->accion)] ?? 'blue';
                        @endphp
                        <span class="badge {{ $color }}">
                            <span class="badge-dot"></span>
                            {{ ucfirst($b->accion) }}
                        </span>
                    </td>

                    <td style="color:var(--text2); font-size:12px; max-width:280px;">
                        {{ $b->descripcion }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

<script>
$(document).ready(function () {
    const table = $('#tablaBitacora').DataTable({
        pageLength: 15,
        order: [[1, 'desc']],
        language: {
            info:     'Mostrando _START_ – _END_ de _TOTAL_ registros',
            infoEmpty: 'Sin registros',
            paginate: { previous: '←', next: '→' },
        },
        columnDefs: [{ orderable: false, targets: [] }],
    });

    $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>

</x-app-layout>