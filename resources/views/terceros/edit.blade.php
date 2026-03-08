<x-app-layout>
    <x-slot name="header">Editar tercero</x-slot>

    <div class="card" style="max-width:900px; margin:0 auto;">
        <div class="card-header">
            <div class="card-title">Actualizar tercero</div>
        </div>

        <form method="POST" action="{{ route('terceros.update', $tercero->id) }}" id="formTerceroEdit">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Razón social</label>
                    <input type="text" name="razon_social" class="form-control" value="{{ old('razon_social', $tercero->razon_social) }}" required>
                </div>

                <div class="form-group">
                    <label>RUT</label>
                    <input type="text" name="rut" id="rut" class="form-control" value="{{ old('rut', $tercero->rut) }}" required>
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control" required>
                        <option value="cliente" {{ old('tipo', $tercero->tipo) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                        <option value="proveedor" {{ old('tipo', $tercero->tipo) === 'proveedor' ? 'selected' : '' }}>Proveedor</option>
                        <option value="ambos" {{ old('tipo', $tercero->tipo) === 'ambos' ? 'selected' : '' }}>Ambos</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $tercero->direccion) }}">
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $tercero->telefono) }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $tercero->email) }}">
                </div>

                <div class="form-group">
                    <label>Banco</label>
                    <input type="text" name="banco" class="form-control" value="{{ old('banco', $tercero->banco) }}">
                </div>

                <div class="form-group">
                    <label>Tipo de cuenta</label>
                    <select name="tipo_cuenta" class="form-control">
                        <option value="" {{ old('tipo_cuenta', $tercero->tipo_cuenta) === null ? 'selected' : '' }}>Seleccione</option>
                        <option value="corriente" {{ old('tipo_cuenta', $tercero->tipo_cuenta) === 'corriente' ? 'selected' : '' }}>Cuenta corriente</option>
                        <option value="vista" {{ old('tipo_cuenta', $tercero->tipo_cuenta) === 'vista' ? 'selected' : '' }}>Cuenta vista</option>
                        <option value="ahorro" {{ old('tipo_cuenta', $tercero->tipo_cuenta) === 'ahorro' ? 'selected' : '' }}>Cuenta de ahorro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Número de cuenta</label>
                    <input type="text" name="numero_cuenta" class="form-control" value="{{ old('numero_cuenta', $tercero->numero_cuenta) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control" required>
                        <option value="1" {{ old('estado', $tercero->estado) ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !old('estado', $tercero->estado) ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('terceros.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-save">Guardar cambios</button>
            </div>
        </form>
    </div>

    <script>
    function normalizarRut(valor) {
        let limpio = (valor || '').replace(/[^0-9kK]/g, '').toUpperCase();
        if (limpio.length < 2) return limpio;
        return limpio.slice(0, -1) + '-' + limpio.slice(-1);
    }

    function rutValido(rut) {
        const valor = normalizarRut(rut);
        if (!valor.includes('-')) return false;

        const partes = valor.split('-');
        const cuerpo = partes[0];
        const dv = partes[1];

        if (!/^\d{7,8}$/.test(cuerpo)) return false;

        let suma = 0;
        let multiplicador = 2;

        for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += parseInt(cuerpo.charAt(i), 10) * multiplicador;
            multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
        }

        const resto = 11 - (suma % 11);
        const esperado = resto === 11 ? '0' : resto === 10 ? 'K' : String(resto);

        return esperado === dv.toUpperCase();
    }

    const rutInput = document.getElementById('rut');
    if (rutInput) {
        rutInput.addEventListener('input', function () {
            this.value = normalizarRut(this.value);
        });
    }

    const form = document.getElementById('formTerceroEdit');
    if (form) {
        form.addEventListener('submit', function (event) {
            const rut = rutInput ? rutInput.value : '';
            if (!rutValido(rut)) {
                event.preventDefault();
                alert('El RUT ingresado no es válido.');
            }
        });
    }
    </script>
</x-app-layout>
