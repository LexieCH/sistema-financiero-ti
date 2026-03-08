<x-app-layout>
    <x-slot name="header">Editar empresa</x-slot>

    <div class="card" style="max-width:900px; margin:0 auto;">
        <div class="card-header">
            <div class="card-title">Actualizar empresa</div>
        </div>

        <form method="POST" action="{{ route('empresas.update', $empresa->id) }}" id="formEmpresaEdit">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre de fantasía</label>
                    <input type="text" name="nombre_fantasia" class="form-control" value="{{ old('nombre_fantasia', $empresa->nombre_fantasia) }}" required>
                </div>

                <div class="form-group">
                    <label>Razón social</label>
                    <input type="text" name="razon_social" class="form-control" value="{{ old('razon_social', $empresa->razon_social) }}" required>
                </div>

                <div class="form-group">
                    <label>RUT</label>
                    <input type="text" name="rut_empresa" id="rut_empresa" class="form-control" value="{{ old('rut_empresa', $empresa->rut_empresa) }}" required>
                </div>

                <div class="form-group">
                    <label>Giro</label>
                    <input type="text" name="giro" class="form-control" value="{{ old('giro', $empresa->giro) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control" required>
                        <option value="activa" {{ old('estado', $empresa->estado) === 'activa' ? 'selected' : '' }}>Activa</option>
                        <option value="inactiva" {{ old('estado', $empresa->estado) === 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('empresas.index') }}" class="btn-cancel">Cancelar</a>
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

    const rutInput = document.getElementById('rut_empresa');
    if (rutInput) {
        rutInput.addEventListener('input', function () {
            this.value = normalizarRut(this.value);
        });
    }

    const form = document.getElementById('formEmpresaEdit');
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
