<x-app-layout>

<x-slot name="header">
Editar tipo de documento
</x-slot>

<div class="card">
    <form method="POST" action="{{ route('tipos-documentos.update', $tipoDocumento) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $tipoDocumento->nombre) }}" required>
            </div>

            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria" class="form-control" required>
                    <option value="compra" {{ old('categoria', $tipoDocumento->categoria) === 'compra' ? 'selected' : '' }}>Compra</option>
                    <option value="venta" {{ old('categoria', $tipoDocumento->categoria) === 'venta' ? 'selected' : '' }}>Venta</option>
                    <option value="interno" {{ old('categoria', $tipoDocumento->categoria) === 'interno' ? 'selected' : '' }}>Interno</option>
                </select>
            </div>

            <div class="form-group">
                <label>Usa IVA</label>
                <select name="usa_iva" class="form-control" required>
                    <option value="1" {{ old('usa_iva', $tipoDocumento->usa_iva) ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !old('usa_iva', $tipoDocumento->usa_iva) ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="form-group">
                <label>Crédito fiscal</label>
                <select name="credito_fiscal" class="form-control" required>
                    <option value="1" {{ old('credito_fiscal', $tipoDocumento->credito_fiscal) ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !old('credito_fiscal', $tipoDocumento->credito_fiscal) ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="form-group">
                <label>Genera movimiento</label>
                <select name="genera_movimiento" class="form-control" required>
                    <option value="1" {{ old('genera_movimiento', $tipoDocumento->genera_movimiento) ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !old('genera_movimiento', $tipoDocumento->genera_movimiento) ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tipo de movimiento</label>
                <select name="tipo_movimiento_id" class="form-control">
                    <option value="">— Sin tipo asociado —</option>
                    @foreach($tiposMovimiento as $tipoMovimiento)
                        <option value="{{ $tipoMovimiento->id }}" {{ (int) old('tipo_movimiento_id', $tipoDocumento->tipo_movimiento_id) === (int) $tipoMovimiento->id ? 'selected' : '' }}>
                            {{ $tipoMovimiento->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control" required>
                    <option value="activo" {{ old('estado', $tipoDocumento->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $tipoDocumento->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('tipos-documentos.index') }}" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-save">Guardar cambios</button>
        </div>
    </form>
</div>

</x-app-layout>
