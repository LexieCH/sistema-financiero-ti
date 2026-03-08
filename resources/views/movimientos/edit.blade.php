<x-app-layout>

    <x-slot name="header">
        Editar Movimiento
    </x-slot>


    <div class="page-header">
        <h2>Editar movimiento financiero</h2>
        <p>Actualiza los datos del movimiento seleccionado</p>
    </div>


    <div class="card">

        <div class="card-header">
            <div class="card-title">
                Datos del movimiento
            </div>
        </div>


        <form method="POST" action="{{ route('movimientos.update', $movimiento->id) }}">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Tipo de movimiento <span style="color:var(--red)">*</span></label>
                    <select name="tipo_movimiento_id" class="form-control" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ (int) old('tipo_movimiento_id', $movimiento->tipo_movimiento_id) === (int) $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Categoría <span style="color:var(--red)">*</span></label>
                    <select name="categoria_id" class="form-control" required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ (int) old('categoria_id', $movimiento->categoria_id) === (int) $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Centro de costo <span style="color:var(--red)">*</span></label>
                    <select name="centro_costo_id" class="form-control" required>
                        @foreach($centros as $centro)
                            <option value="{{ $centro->id }}" {{ (int) old('centro_costo_id', $movimiento->centro_costo_id) === (int) $centro->id ? 'selected' : '' }}>
                                {{ $centro->proyecto->nombre ?? 'Sin proyecto' }} - {{ $centro->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Método de pago</label>
                    <select name="metodo_pago_id" class="form-control">
                        <option value="">— Seleccionar —</option>
                        @foreach($metodos as $metodo)
                            <option value="{{ $metodo->id }}" {{ (int) old('metodo_pago_id', $movimiento->metodo_pago_id) === (int) $metodo->id ? 'selected' : '' }}>
                                {{ $metodo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Cliente / Proveedor</label>
                    <select name="tercero_id" class="form-control">
                        <option value="">— Seleccionar —</option>
                        @foreach($terceros as $tercero)
                            <option value="{{ $tercero->id }}" {{ (int) old('tercero_id', $movimiento->tercero_id) === (int) $tercero->id ? 'selected' : '' }}>
                                {{ $tercero->razon_social }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Monto (CLP $) <span style="color:var(--red)">*</span></label>
                    <input type="number" step="0.01" name="monto" class="form-control" value="{{ old('monto', $movimiento->monto) }}" required>
                </div>

                <div class="form-group">
                    <label>Fecha y hora <span style="color:var(--red)">*</span></label>
                    <input type="datetime-local" name="fecha" class="form-control" value="{{ old('fecha', \Carbon\Carbon::parse($movimiento->fecha)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="form-group form-col-2">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $movimiento->descripcion) }}</textarea>
                </div>

            </div>

            <div class="form-footer">
                <a href="{{ route('movimientos.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-save">Guardar cambios</button>
            </div>

        </form>

    </div>

</x-app-layout>
