<x-app-layout>
    <x-slot name="header">Terceros</x-slot>

    <div class="page-header">
        <h2>Gestión de Terceros</h2>
        <p>Clientes y proveedores registrados en el sistema</p>
    </div>

    <div class="card">
        <div class="dt-controls">
            <div class="card-title">Listado general</div>
            <div class="card-actions">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input class="search-input" id="search-ter" placeholder="Buscar tercero...">
                </div>
                <button onclick="abrirModal()" class="btn-primary" style="font-size:12px;padding:7px 14px;">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nuevo tercero
                </button>
            </div>
        </div>

        <table id="tablaTerceros" class="dataTable no-footer" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Razón Social</th>
                    <th>RUT</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($terceros as $tercero)
                <tr>
                    <td class="id-cell">{{ str_pad($tercero->id, 3, '0', STR_PAD_LEFT) }}</td>

                    <td style="font-weight:600">{{ $tercero->razon_social }}</td>

                    <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text2)">
                        {{ $tercero->rut }}
                    </td>

                    <td>
                        @if(strtolower($tercero->tipo) === 'cliente')
                            <span class="badge blue"><span class="badge-dot"></span>Cliente</span>
                        @elseif(strtolower($tercero->tipo) === 'proveedor')
                            <span class="badge yellow"><span class="badge-dot"></span>Proveedor</span>
                        @else
                            <span class="badge blue"><span class="badge-dot"></span>{{ ucfirst($tercero->tipo) }}</span>
                        @endif
                    </td>

                    <td>
                        <span class="badge green"><span class="badge-dot"></span>Activo</span>
                    </td>

                    <td style="display:flex;gap:6px;">

                        <a href="{{ route('terceros.show', $tercero->id) }}" class="btn-sm success">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M2.05 12a10 10 0 0119.9 0 10 10 0 01-19.9 0z"/>
                            </svg>
                            Ver más
                        </a>

                        <button class="btn-sm">
                            <svg viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Editar
                        </button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL --}}
    <div id="modalTercero" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Registrar nuevo tercero</div>
                <button class="modal-close" onclick="cerrarModal()">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('terceros.store') }}">
            @csrf

            <div class="modal-body">
                <div class="form-group">
                    <label>Razón social <span style="color:var(--red)">*</span></label>
                    <input type="text" class="form-control" placeholder="Nombre legal">
                </div>
                <div class="form-group">
                    <label>RUT <span style="color:var(--red)">*</span></label>
                    <input type="text"
                            id="rut"
                            name="rut"
                            class="form-control"
                            placeholder="Ej: 11111111-1"
                            required>
                </div>
                <div class="form-group">
                    <label>Tipo <span style="color:var(--red)">*</span></label>
                    <select class="form-control">
                        <option value="cliente">Cliente</option>
                        <option value="proveedor">Proveedor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Banco</label>
                    <input type="text" name="banco" class="form-control" placeholder="Ej: Banco Estado">
                </div>

                <div class="form-group">
                    <label>Tipo de cuenta</label>
                    <select name="tipo_cuenta" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="corriente">Cuenta corriente</option>
                        <option value="vista">Cuenta vista</option>
                        <option value="ahorro">Cuenta de ahorro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Número de cuenta</label>
                    <input type="text" name="numero_cuenta" class="form-control" placeholder="Número de cuenta">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarModal()" class="btn-cancel">Cancelar</button>
                <button type="submit" class="btn-save">Guardar</button>
            </div>
            </form>
        </div>
    </div>

    <script>

    function abrirModal()  { 
        document.getElementById('modalTercero').classList.add('open'); 
    }

    function cerrarModal() { 
        document.getElementById('modalTercero').classList.remove('open'); 
    }

    document.getElementById('modalTercero').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });


    $(document).ready(function () {

        var dt = $('#tablaTerceros').DataTable({
            pageLength: 10,
            language: {
                paginate: { previous: '←', next: '→' },
                info: 'Mostrando _START_ – _END_ de _TOTAL_ registros',
                infoEmpty: 'Sin registros',
                emptyTable: 'No hay terceros registrados',
            },
            dom: 'tip',
            columnDefs: [{ orderable: false, targets: -1 }],
        });

        $('#search-ter').on('keyup', function () { 
            dt.search(this.value).draw(); 
        });

    });


    // NORMALIZAR RUT AUTOMÁTICAMENTE
    const rutInput = document.getElementById('rut');

    if (rutInput) {

        rutInput.addEventListener('input', function () {

            let valor = this.value;

            // eliminar puntos y guiones
            valor = valor.replace(/\./g, '').replace(/-/g, '');

            if (valor.length > 1) {

                let cuerpo = valor.slice(0, -1);
                let dv = valor.slice(-1).toUpperCase();

                this.value = cuerpo + '-' + dv;

            } else {
                this.value = valor;
            }

        });

    }

    </script>
        

</x-app-layout>