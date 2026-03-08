<x-app-layout>
    <x-slot name="header">Empresas</x-slot>

    @php
        // Esta variable la usamos para no repetir Auth::user() por toda la vista.
        $usuarioAuth = Auth::user();
    @endphp

    <div class="page-header">
        <h2>Gestión de Empresas</h2>
        <p>Administra las empresas registradas en el sistema multiempresa</p>
    </div>

    <div class="card">
        <div class="dt-controls">
            <div class="card-title">Listado general</div>
            <div class="card-actions">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input class="search-input" id="search-emp" placeholder="Buscar empresa...">
                </div>
                @if($usuarioAuth->tienePermiso('empresas', 'crear'))
                <button onclick="abrirModal()" class="btn-primary" style="font-size:12px;padding:7px 14px;">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nueva empresa
                </button>
                @endif
            </div>
        </div>

        <table id="tablaEmpresas" class="dataTable no-footer" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Empresa</th>
                    <th>RUT</th>
                    <th>Giro</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empresas ?? [] as $empresa)
                <tr>
                    <td class="id-cell">{{ str_pad($empresa->id, 3, '0', STR_PAD_LEFT) }}</td>

                    <td>
                        <div style="font-weight:600">{{ $empresa->nombre_fantasia }}</div>
                        <div style="font-size:11px;color:var(--muted)">{{ $empresa->razon_social }}</div>
                    </td>

                    <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text2)">
                        {{ $empresa->rut_empresa }}
                    </td>

                    <td style="font-size:12px;color:var(--text2);max-width:200px;">
                        {{ $empresa->giro }}
                    </td>

                    <td>
                        @if($empresa->estado === 'activa' || $empresa->estado === 'activo')
                            <span class="badge green"><span class="badge-dot"></span>Activa</span>
                        @else
                            <span class="badge red"><span class="badge-dot"></span>Inactiva</span>
                        @endif
                    </td>

                    <td style="display:flex;gap:6px;">
                        @if($usuarioAuth->tienePermiso('empresas', 'editar'))
                        <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn-sm">
                            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Editar
                        </a>
                        @endif

                        @if($usuarioAuth->tienePermiso('empresas', 'eliminar'))
                        <form method="POST" action="{{ route('empresas.destroy', $empresa->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn-sm danger" onclick="return confirm('¿Desactivar empresa?')">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                                Desactivar
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($usuarioAuth->tienePermiso('empresas', 'crear'))
    {{-- MODAL --}}
    <div id="modalEmpresa" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Registrar nueva empresa</div>
                <button class="modal-close" onclick="cerrarModal()">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('empresas.store') }}" id="formEmpresa">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label>Nombre de fantasía <span style="color:var(--red)">*</span></label>
                        <input type="text" name="nombre_fantasia" class="form-control" placeholder="Ej: Mi Empresa" required>
                    </div>

                    <div class="form-group">
                        <label>Razón social <span style="color:var(--red)">*</span></label>
                        <input type="text" name="razon_social" class="form-control" placeholder="Ej: Mi Empresa SpA" required>
                    </div>

                    <div class="form-group">
                        <label>RUT <span style="color:var(--red)">*</span></label>
                        <input type="text" name="rut_empresa" id="rut_empresa" class="form-control" placeholder="Ej: 76123456-7" required>
                    </div>

                    <div class="form-group">
                        <label>Giro</label>
                        <input type="text" name="giro" class="form-control" placeholder="Actividad económica">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" onclick="cerrarModal()" class="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
    function abrirModal()  { document.getElementById('modalEmpresa').classList.add('open'); }
    function cerrarModal() { document.getElementById('modalEmpresa').classList.remove('open'); }

    // Cerrar al click fuera del modal
    document.getElementById('modalEmpresa').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    $(document).ready(function () {
        var dt = $('#tablaEmpresas').DataTable({
            pageLength: 10,
            language: {
                paginate: { previous: '←', next: '→' },
                info: 'Mostrando _START_ – _END_ de _TOTAL_ registros',
                infoEmpty: 'Sin registros',
                emptyTable: 'No hay empresas registradas',
            },
            dom: 'tip',
            columnDefs: [{ orderable: false, targets: -1 }],
        });
        $('#search-emp').on('keyup', function () { dt.search(this.value).draw(); });
    });

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

    const rutEmpresaInput = document.getElementById('rut_empresa');
    if (rutEmpresaInput) {
        rutEmpresaInput.addEventListener('input', function () {
            this.value = normalizarRut(this.value);
        });
    }

    const formEmpresa = document.getElementById('formEmpresa');
    if (formEmpresa) {
        formEmpresa.addEventListener('submit', function (event) {
            const rut = rutEmpresaInput ? rutEmpresaInput.value : '';
            if (!rutValido(rut)) {
                event.preventDefault();
                alert('El RUT ingresado no es válido.');
            }
        });
    }
    </script>

</x-app-layout>