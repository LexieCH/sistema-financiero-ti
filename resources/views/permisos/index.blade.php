<x-app-layout>
    <x-slot name="header">Permisos por módulo</x-slot>

    <div class="page-header">
        <h2>Permisos por rol</h2>
        <p>Configura lectura, crear, editar y eliminar por módulo</p>
    </div>

    <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
            <div class="card-title">Seleccionar rol</div>
        </div>

        <form method="GET" action="{{ route('permisos.index') }}" style="padding:20px; display:flex; gap:10px; align-items:flex-end;">
            <div class="form-group" style="max-width:320px; width:100%; margin:0;">
                <label>Rol</label>
                <select name="rol_id" class="form-control" onchange="this.form.submit()">
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" {{ ($rolSeleccionado?->id === $rol->id) ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    @if($rolSeleccionado)
    <form method="POST" action="{{ route('permisos.update', $rolSeleccionado->id) }}">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <div class="card-title">Matriz de permisos: {{ $rolSeleccionado->nombre }}</div>
            </div>

            <table class="dataTable no-footer" style="width:100%">
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th>Lectura</th>
                        <th>Crear</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($modulos as $modulo)
                    @php($permiso = $permisos->get($modulo->id))
                    <tr>
                        <td style="font-weight:600;">{{ $modulo->nombre }}</td>
                        <td><input type="checkbox" name="permisos[{{ $modulo->id }}][lectura]" {{ $permiso?->lectura ? 'checked' : '' }}></td>
                        <td><input type="checkbox" name="permisos[{{ $modulo->id }}][crear]" {{ $permiso?->crear ? 'checked' : '' }}></td>
                        <td><input type="checkbox" name="permisos[{{ $modulo->id }}][editar]" {{ $permiso?->editar ? 'checked' : '' }}></td>
                        <td><input type="checkbox" name="permisos[{{ $modulo->id }}][eliminar]" {{ $permiso?->eliminar ? 'checked' : '' }}></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="form-footer">
                <a href="{{ route('dashboard') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-save">Guardar cambios</button>
            </div>
        </div>
    </form>
    @endif
</x-app-layout>
