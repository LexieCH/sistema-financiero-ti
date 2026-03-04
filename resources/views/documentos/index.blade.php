<x-app-layout>
    <x-slot name="header">Documentos</x-slot>

    {{-- KPI --}}
    <div class="kpi-grid kpi-3">

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-icon yellow">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="kpi-trend neu">Pendiente</span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">Monto pendiente</div>
                <div class="kpi-value yellow">${{ number_format($totalPendiente, 0, ',', '.') }}</div>
                <div class="kpi-sub">Por cobrar / pagar</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-icon green">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <span class="kpi-trend up">
                    <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                    Pagado
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">Monto pagado</div>
                <div class="kpi-value green">${{ number_format($totalPagado, 0, ',', '.') }}</div>
                <div class="kpi-sub">Cobrado / pagado</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-icon blue">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <span class="kpi-trend neu">Total</span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">Total general</div>
                <div class="kpi-value blue">${{ number_format($totalGeneral, 0, ',', '.') }}</div>
                <div class="kpi-sub">Todos los documentos</div>
            </div>
        </div>

    </div>

    {{-- FORMULARIO --}}
    <div class="card" style="margin-bottom:24px;">
        <div class="card-header">
            <div class="card-title">Registrar nuevo documento</div>
        </div>

        <form method="POST" action="{{ route('documentos.store') }}">
            @csrf
            <div class="form-grid">

                <div class="form-group">
                    <label>Tipo de documento <span style="color:var(--red)">*</span></label>
                    <select name="tipo_documento_id" class="form-control" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha de emisión <span style="color:var(--red)">*</span></label>
                    <input type="date" name="fecha_emision" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Monto neto <span style="color:var(--red)">*</span></label>
                    <input type="number" step="0.01" name="monto_neto" id="monto_neto"
                           class="form-control" placeholder="0" required>
                </div>

                <div class="form-group">
                    <label>IVA (19%)</label>
                    <input type="text" id="iva_display" class="form-control"
                           style="background:var(--surface2);color:var(--muted)" readonly placeholder="Calculado automáticamente">
                </div>

                <div class="form-group form-col-2">
                    <label>Total</label>
                    <input type="text" id="total_display" class="form-control"
                           style="background:var(--surface2);font-weight:700;color:var(--accent)" readonly placeholder="Calculado automáticamente">
                </div>

            </div>
            <div class="form-footer">
                <button type="submit" class="btn-save">Guardar documento</button>
            </div>
        </form>
    </div>

    {{-- TABLA --}}
    <div class="card">
        <div class="dt-controls">
            <div class="card-title">Listado de documentos</div>
            <div class="card-actions">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input class="search-input" id="search-doc" placeholder="Buscar documento...">
                </div>
            </div>
        </div>

        <table id="tablaDocumentos" class="dataTable no-footer" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Tipo</th>
                    <th>Fecha emisión</th>
                    <th>Monto neto</th>
                    <th>IVA (19%)</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documentos as $doc)
                <tr>
                    <td class="id-cell">#{{ str_pad($doc->id, 3, '0', STR_PAD_LEFT) }}</td>

                    <td style="font-weight:600">{{ $doc->tipoDocumento->nombre ?? '—' }}</td>

                    <td style="font-size:12px;color:var(--text2)">
                        {{ \Carbon\Carbon::parse($doc->fecha_emision)->format('d-m-Y') }}
                    </td>

                    <td class="amount">${{ number_format($doc->monto_neto ?? 0, 0, ',', '.') }}</td>

                    <td class="amount" style="color:var(--muted)">
                        ${{ number_format(($doc->monto_neto ?? 0) * 0.19, 0, ',', '.') }}
                    </td>

                    <td class="amount" style="color:var(--text);font-weight:700">
                        ${{ number_format($doc->total, 0, ',', '.') }}
                    </td>

                    <td>
                        @if($doc->estado === 'pendiente')
                            <span class="badge yellow"><span class="badge-dot"></span>Pendiente</span>
                        @elseif($doc->estado === 'pagado')
                            <span class="badge green"><span class="badge-dot"></span>Pagado</span>
                        @elseif($doc->estado === 'vencido')
                            <span class="badge red"><span class="badge-dot"></span>Vencido</span>
                        @else
                            <span class="badge blue"><span class="badge-dot"></span>{{ ucfirst($doc->estado) }}</span>
                        @endif
                    </td>

                    <td>
                        @if($doc->estado === 'pendiente')
                            <form method="POST" action="{{ route('documentos.pagado', $doc->id) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-sm success">
                                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    Marcar pagado
                                </button>
                            </form>
                        @else
                            <button class="btn-sm">
                                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Ver
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
    // IVA calculator
    document.getElementById('monto_neto').addEventListener('input', function () {
        var neto  = parseFloat(this.value) || 0;
        var iva   = Math.round(neto * 0.19);
        var total = neto + iva;
        document.getElementById('iva_display').value   = '$' + iva.toLocaleString('es-CL');
        document.getElementById('total_display').value = '$' + total.toLocaleString('es-CL');
    });

    // DataTable
    $(document).ready(function () {
        var dt = $('#tablaDocumentos').DataTable({
            pageLength: 10,
            language: {
                paginate: { previous: '←', next: '→' },
                info: 'Mostrando _START_ – _END_ de _TOTAL_ registros',
                infoEmpty: 'Sin registros',
                emptyTable: 'No hay documentos registrados',
            },
            dom: 'tip',
            columnDefs: [{ orderable: false, targets: -1 }],
            order: [[2, 'desc']],
        });
        $('#search-doc').on('keyup', function () { dt.search(this.value).draw(); });
    });
    </script>

</x-app-layout>
