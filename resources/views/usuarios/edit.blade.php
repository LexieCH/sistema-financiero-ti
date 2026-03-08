<x-app-layout>
    <x-slot name="header">Editar usuario</x-slot>

    <div class="card" style="max-width:900px; margin:0 auto;">
        <div class="card-header">
            <div class="card-title">Actualizar usuario</div>
        </div>

        <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Solo si quieres cambiarla">
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol_id" class="form-control" required>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ (int) old('rol_id', $usuario->rol_id) === (int) $rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Empresa</label>
                    <select name="empresa_id" class="form-control">
                        <option value="">— Sin empresa —</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ (int) old('empresa_id', $usuario->empresa_id) === (int) $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre_fantasia }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control" required>
                        <option value="activo" {{ old('estado', $usuario->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $usuario->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('usuarios.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-save">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
