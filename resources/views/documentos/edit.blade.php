<x-app-layout>

<x-slot name="header">
Editar documento
</x-slot>

<div class="card" style="max-width:900px; margin:0 auto;">
    <div class="card-header">
        <div class="card-title">Actualizar documento</div>
    </div>

    <form method="POST" action="{{ route('documentos.update', $documento->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Tipo documento</label>
                <select name="tipo_documento_id" class="form-control" required>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ (int) old('tipo_documento_id', $documento->tipo_documento_id) === (int) $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cliente / Proveedor</label>
                <select name="tercero_id" class="form-control" required>
                    <option value="">Seleccione</option>
                    @foreach($terceros as $tercero)
                        <option value="{{ $tercero->id }}" {{ (int) old('tercero_id', $documento->tercero_id) === (int) $tercero->id ? 'selected' : '' }}>
                            {{ $tercero->rut ?? 'Sin RUT' }} - {{ $tercero->razon_social }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Fecha emisión</label>
                <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision', $documento->fecha_emision) }}" required>
            </div>

            <div class="form-group">
                <label>Fecha vencimiento</label>
                <input type="date" name="fecha_vencimiento" class="form-control" value="{{ old('fecha_vencimiento', $documento->fecha_vencimiento) }}">
            </div>

            <div class="form-group">
                <label>Monto neto</label>
                <input type="number" step="0.01" name="monto_neto" class="form-control" value="{{ old('monto_neto', $documento->monto_neto) }}" required>
            </div>

            <div class="form-group">
                <label>PDF adjunto</label>
                <input type="file" name="pdf" class="form-control" accept="application/pdf">

                @if($documento->pdf_url)
                    <a href="{{ asset('storage/' . $documento->pdf_url) }}" target="_blank" class="btn-sm" style="margin-top:8px;display:inline-block;">
                        Ver PDF actual
                    </a>
                @endif
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('documentos.index') }}" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-save">Guardar cambios</button>
        </div>
    </form>
</div>

</x-app-layout>
