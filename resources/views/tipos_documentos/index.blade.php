<x-app-layout>

<x-slot name="header">
Tipos de documento
</x-slot>

@php
    $usuarioAuth = Auth::user();
@endphp

<div class="page-header">
    <h2>Mantenedor de tipos de documento</h2>
    <p>Aquí puedes crear y administrar facturas, boletas, notas y otros tipos. El movimiento financiero se genera al registrar el pago.</p>
</div>

@if($usuarioAuth->tienePermiso('tipos-documentos', 'crear'))
<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title">Nuevo tipo de documento</div>
    </div>

    <form method="POST" action="{{ route('tipos-documentos.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Clasificación financiera</label>
                <select name="categoria" id="categoria_create" class="form-control" required>
                    <option value="venta">Venta (Factura emitida → Ingreso)</option>
                    <option value="compra">Compra (Factura recibida → Egreso)</option>
                    <option value="interno">Interno (ajuste/operación interna)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Usa IVA</label>
                <select name="usa_iva" class="form-control" required>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="form-group">
                <label>Crédito fiscal</label>
                <select name="credito_fiscal" class="form-control" required>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tipo de movimiento al pagar</label>
                <select name="tipo_movimiento_id" id="tipo_movimiento_create" class="form-control" disabled>
                    <option value="">— Sin tipo asociado —</option>
                    @foreach($tiposMovimiento as $tipoMovimiento)
                        <option value="{{ $tipoMovimiento->id }}">{{ $tipoMovimiento->nombre }}</option>
                    @endforeach
                </select>
                <small style="font-size:11px;color:var(--muted);display:block;margin-top:4px;">
                    Solo aplica para clasificación Interno.
                </small>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('tipos-documentos.index') }}" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-save">Guardar</button>
        </div>
    </form>
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">Listado de tipos de documento</div>
    </div>

    <table class="dataTable no-footer" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Clasificación</th>
                <th>Movimiento asociado</th>
                <th>IVA</th>
                <th>Crédito</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipos as $tipo)
            <tr>
                <td class="id-cell">#{{ str_pad($tipo->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $tipo->nombre }}</td>
                <td>{{ ucfirst($tipo->categoria) }}</td>
                <td>{{ $tipo->tipoMovimiento->nombre ?? 'Automático por clasificación' }}</td>
                <td>{{ $tipo->usa_iva ? 'Sí' : 'No' }}</td>
                <td>{{ $tipo->credito_fiscal ? 'Sí' : 'No' }}</td>
                <td>
                    @if($tipo->estado === 'activo')
                        <span class="badge green"><span class="badge-dot"></span>Activo</span>
                    @else
                        <span class="badge red"><span class="badge-dot"></span>Inactivo</span>
                    @endif
                </td>
                <td style="display:flex; gap:6px;">
                    @if($usuarioAuth->tienePermiso('tipos-documentos', 'editar'))
                    <a href="{{ route('tipos-documentos.edit', $tipo) }}" class="btn-sm">Editar</a>
                    @endif

                    @if($usuarioAuth->tienePermiso('tipos-documentos', 'eliminar'))
                    <form method="POST" action="{{ route('tipos-documentos.destroy', $tipo) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-sm danger" onclick="return confirm('¿Desactivar tipo de documento?')">Desactivar</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoria = document.getElementById('categoria_create');
        const tipoMovimiento = document.getElementById('tipo_movimiento_create');

        if (!categoria || !tipoMovimiento) {
            return;
        }

        const syncTipoMovimiento = function () {
            const esInterno = categoria.value === 'interno';
            tipoMovimiento.disabled = !esInterno;

            if (!esInterno) {
                tipoMovimiento.value = '';
            }
        };

        syncTipoMovimiento();
        categoria.addEventListener('change', syncTipoMovimiento);
    });
</script>

</x-app-layout>
