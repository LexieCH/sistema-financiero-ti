<x-app-layout>
    <x-slot name="header">Usuarios</x-slot>

    <div class="page-header">
        <h2>Gestión de Usuarios</h2>
        <p>Administra los usuarios y sus permisos en el sistema</p>
    </div>

    <div class="card">
        <div class="dt-controls">
            <div class="card-title">Listado general</div>
            <div class="card-actions">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input class="search-input" id="search-usr" placeholder="Buscar usuario...">
                </div>
                <button onclick="abrirModal()" class="btn-primary" style="font-size:12px;padding:7px 14px;">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nuevo usuario
                </button>
            </div>
        </div>

        <table id="tablaUsuarios" class="dataTable no-footer" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Empresa</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                <tr>
                    <td class="id-cell">{{ str_pad($usuario->id, 3, '0', STR_PAD_LEFT) }}</td>

                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($usuario->name, 0, 2)) }}
                            </div>
                            <span style="font-weight:600">{{ $usuario->name }}</span>
                        </div>
                    </td>

                    <td style="font-size:12px;color:var(--text2)">{{ $usuario->email }}</td>

                    <td style="font-size:12px;color:var(--text2)">
                        {{ $usuario->empresa->nombre_fantasia ?? '—' }}
                    </td>

                    <td>
                        <span class="badge blue"><span class="badge-dot"></span>{{ $usuario->rol->nombre ?? '—' }}</span>
                    </td>

                    <td>
                        @if(($usuario->estado ?? 'activo') === 'activo')
                            <span class="badge green"><span class="badge-dot"></span>Activo</span>
                        @else
                            <span class="badge red"><span class="badge-dot"></span>Inactivo</span>
                        @endif
                    </td>

                    <td>
                        <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm danger"
                                    onclick="return confirm('¿Desactivar este usuario?')">
                                <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/></svg>
                                Desactivar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL CREAR USUARIO --}}
    <div id="modalUsuario" class="modal-overlay">

        <div class="modal-box">

            <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf

                <div class="modal-header">
                    <div class="modal-title">Registrar nuevo usuario</div>

                    <button type="button" class="modal-close" onclick="cerrarModal()">
                        <svg viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>


                <div class="modal-body">

                    {{-- Nombre --}}
                    <div class="form-group">
                        <label>Nombre completo <span style="color:var(--red)">*</span></label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Nombre del usuario"
                            required
                        >
                    </div>


                    {{-- Email --}}
                    <div class="form-group">
                        <label>Correo electrónico <span style="color:var(--red)">*</span></label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="correo@empresa.cl"
                            required
                        >
                    </div>


                    {{-- Password --}}
                    <div class="form-group">
                        <label>Contraseña <span style="color:var(--red)">*</span></label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Contraseña"
                            required
                        >
                    </div>


                    {{-- Rol --}}
                    <div class="form-group">
                        <label>Rol <span style="color:var(--red)">*</span></label>

                        <select name="rol_id" class="form-control" required>

                            <option value="">
                                — Seleccionar rol —
                            </option>

                            @foreach($roles as $rol)

                                <option value="{{ $rol->id }}">
                                    {{ $rol->nombre }}
                                </option>

                            @endforeach

                        </select>
                    </div>


                    {{-- Empresa --}}
                    <div class="form-group">
                        <label>Empresa</label>

                        <select name="empresa_id" class="form-control">

                            <option value="">
                                — Seleccionar empresa —
                            </option>

                            @foreach($empresas as $empresa)

                                <option value="{{ $empresa->id }}">
                                    {{ $empresa->nombre_fantasia }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        onclick="cerrarModal()"
                        class="btn-cancel"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn-save"
                    >
                        Guardar usuario
                    </button>

                </div>

            </form>

        </div>

</div>

    <script>
    function abrirModal()  { document.getElementById('modalUsuario').classList.add('open'); }
    function cerrarModal() { document.getElementById('modalUsuario').classList.remove('open'); }
    document.getElementById('modalUsuario').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    $(document).ready(function () {
        var dt = $('#tablaUsuarios').DataTable({
            pageLength: 10,
            language: {
                paginate: { previous: '←', next: '→' },
                info: 'Mostrando _START_ – _END_ de _TOTAL_ registros',
                infoEmpty: 'Sin registros',
                emptyTable: 'No hay usuarios registrados',
            },
            dom: 'tip',
            columnDefs: [{ orderable: false, targets: -1 }],
        });
        $('#search-usr').on('keyup', function () { dt.search(this.value).draw(); });
    });
    </script>

</x-app-layout>
