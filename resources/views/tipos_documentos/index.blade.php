<x-app-layout>

<x-slot name="header">
Tipos de documento
</x-slot>

@php
    $usuarioAuth = Auth::user();
@endphp

<div class="page-header">
    <h2>Mantenedor de tipos de documento</h2>
    <p>Aquí puedes crear y administrar facturas, boletas, notas y otros tipos.</p>
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
                <label>Categoría</label>
                <select name="categoria" class="form-control" required>
                    <option value="compra">Compra</option>
                    <option value="venta">Venta</option>
                    <option value="interno">Interno</option>
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
                <label>Genera movimiento</label>
                <select name="genera_movimiento" class="form-control" required>
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tipo de movimiento</label>
                <select name="tipo_movimiento_id" class="form-control">
                    <option value="">— Sin tipo asociado —</option>
                    @foreach($tiposMovimiento as $tipoMovimiento)
                        <option value="{{ $tipoMovimiento->id }}">{{ $tipoMovimiento->nombre }}</option>
                    @endforeach
                </select>
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
                <th>Categoría</th>
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

</x-app-layout>
