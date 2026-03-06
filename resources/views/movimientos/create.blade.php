<x-app-layout>

    <x-slot name="header">
        Nuevo Movimiento
    </x-slot>


    <div class="page-header">
        <h2>Registrar movimiento financiero</h2>
        <p>Complete los campos para registrar un ingreso o egreso</p>
    </div>


    <div class="card">

        <div class="card-header">
            <div class="card-title">
                Datos del movimiento
            </div>
        </div>


        <form method="POST" action="{{ route('movimientos.store') }}">

            @csrf


            <div class="form-grid">


                {{-- TIPO MOVIMIENTO --}}
                <div class="form-group">

                    <label>
                        Tipo de movimiento
                        <span style="color:var(--red)">*</span>
                    </label>

                    <select name="tipo_movimiento_id" class="form-control" required>

                        @foreach($tipos as $tipo)

                            <option value="{{ $tipo->id }}"
                                {{ old('tipo_movimiento_id') == $tipo->id ? 'selected' : '' }}>

                                {{ $tipo->nombre }}

                            </option>

                        @endforeach

                    </select>

                    @error('tipo_movimiento_id')
                        <span style="font-size:11px;color:var(--red);margin-top:4px;display:block;">
                            {{ $message }}
                        </span>
                    @enderror

                </div>
                {{-- centro de costos  --}}

                <div class="form-group">

                    <label>Centro de costo</label>

                    <select name="centro_costo_id" class="form-control">

                    <option value="">Seleccione</option>

                    @foreach($centros as $centro)
                    <option value="{{ $centro->id }}">
                    {{ $centro->nombre }}
                    </option>
                    @endforeach

                    </select>

                    </div>

                {{-- TERCERO --}}
                <div class="form-group">

                    <label>
                        Cliente / Proveedor
                    </label>

                    <select name="tercero_id" class="form-control">

                        <option value="">
                            — Seleccionar (opcional) —
                        </option>

                        @foreach($terceros as $tercero)

                            <option value="{{ $tercero->id }}"
                                {{ old('tercero_id') == $tercero->id ? 'selected' : '' }}>

                                {{ $tercero->razon_social }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- MONTO --}}
                <div class="form-group">

                    <label>
                        Monto (CLP $)
                        <span style="color:var(--red)">*</span>
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="monto"
                        class="form-control"
                        placeholder="0"
                        value="{{ old('monto') }}"
                        required
                    >

                    @error('monto')
                        <span style="font-size:11px;color:var(--red);margin-top:4px;display:block;">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- FECHA --}}
                <div class="form-group">

                    <label>
                        Fecha y hora
                        <span style="color:var(--red)">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        name="fecha"
                        class="form-control"
                        max="{{ now()->format('Y-m-d\TH:i') }}"
                        value="{{ old('fecha') }}"
                        required
                    >

                    @error('fecha')
                        <span style="font-size:11px;color:var(--red);margin-top:4px;display:block;">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- DESCRIPCION --}}
                <div class="form-group form-col-2">

                    <label>
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        class="form-control"
                        rows="3"
                        placeholder="Descripción del movimiento..."
                    >{{ old('descripcion') }}</textarea>

                </div>


            </div>


            {{-- BOTONES --}}
            <div class="form-footer">

                <a href="{{ route('movimientos.index') }}" class="btn-cancel">
                    Cancelar
                </a>

                <button type="submit" class="btn-save">
                    Guardar movimiento
                </button>

            </div>


        </form>

    </div>


</x-app-layout>