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
                <label>Clasificación financiera</label>
                <select name="categoria" id="categoria_edit" class="form-control" required>
                    <option value="venta" {{ old('categoria', $tipoDocumento->categoria) === 'venta' ? 'selected' : '' }}>Venta (Factura emitida → Ingreso)</option>
                    <option value="compra" {{ old('categoria', $tipoDocumento->categoria) === 'compra' ? 'selected' : '' }}>Compra (Factura recibida → Egreso)</option>
                    <option value="interno" {{ old('categoria', $tipoDocumento->categoria) === 'interno' ? 'selected' : '' }}>Interno (ajuste/operación interna)</option>
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
                <label>Tipo de movimiento al pagar</label>
                <select name="tipo_movimiento_id" id="tipo_movimiento_edit" class="form-control">
                    <option value="">— Sin tipo asociado —</option>
                    @foreach($tiposMovimiento as $tipoMovimiento)
                        <option value="{{ $tipoMovimiento->id }}" {{ (int) old('tipo_movimiento_id', $tipoDocumento->tipo_movimiento_id) === (int) $tipoMovimiento->id ? 'selected' : '' }}>
                            {{ $tipoMovimiento->nombre }}
                        </option>
                    @endforeach
                </select>
                <small style="font-size:11px;color:var(--muted);display:block;margin-top:4px;">
                    Solo aplica para clasificación Interno.
                </small>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoria = document.getElementById('categoria_edit');
        const tipoMovimiento = document.getElementById('tipo_movimiento_edit');

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
