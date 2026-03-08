<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- KPI --}}
    <div class="kpi-grid kpi-4">

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-icon green">
                    <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
                <span class="kpi-trend up">
                    <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                    Mes
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">Ingresos del mes</div>
                <div class="kpi-value green">${{ number_format($ingresosDelMes ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Movimientos de ingreso</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-icon red">
                    <svg viewBox="0 0 24 24"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                </div>
                <span class="kpi-trend down">
                    <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    Mes
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">Egresos del mes</div>
                <div class="kpi-value red">${{ number_format($egresosDelMes ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Movimientos de egreso</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-icon blue">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <span class="kpi-trend up">
                    <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                    Neto
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">Saldo neto</div>
                <div class="kpi-value blue">${{ number_format(($ingresosDelMes ?? 0) - ($egresosDelMes ?? 0), 0, ',', '.') }}</div>
                <div class="kpi-sub">Balance del mes</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-icon yellow">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="kpi-trend neu">Docs</span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">Documentos pendientes</div>
                <div class="kpi-value yellow">${{ number_format($totalPendiente ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Por cobrar / pagar</div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Gastos por centro de costo (mes actual)</div>
        </div>
        <div style="padding: 12px 20px 20px;">
            <table style="width:100%">
                <thead>
                    <tr>
                        <th>Centro de costo</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($gastosCentroCosto ?? [] as $gasto)
                    <tr>
                        <td>{{ $gasto->centroCosto->nombre ?? 'Sin centro' }}</td>
                        <td>${{ number_format($gasto->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center;color:var(--muted)">
                            No hay gastos registrados por centro de costo este mes
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ACCESOS RÁPIDOS --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Acceso rápido</div>
        </div>
        <div style="padding:20px; display:grid; grid-template-columns:repeat(4,1fr); gap:12px;">

            <a href="{{ route('movimientos.create') }}" style="text-decoration:none;">
                <div style="border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center;transition:all .15s;cursor:pointer;" onmouseover="this.style.borderColor='var(--accent)';this.style.background='var(--blue-bg)'" onmouseout="this.style.borderColor='var(--border)';this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:var(--blue-bg);border:1px solid var(--blue-bd);border-radius:8px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--accent);fill:none;stroke-width:2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <div style="font-size:12px;font-weight:600;color:var(--text)">Nuevo movimiento</div>
                </div>
            </a>

            <a href="{{ route('documentos.index') }}" style="text-decoration:none;">
                <div style="border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center;transition:all .15s;" onmouseover="this.style.borderColor='var(--accent)';this.style.background='var(--blue-bg)'" onmouseout="this.style.borderColor='var(--border)';this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:var(--yellow-bg);border:1px solid var(--yellow-bd);border-radius:8px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--yellow);fill:none;stroke-width:2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div style="font-size:12px;font-weight:600;color:var(--text)">Ver documentos</div>
                </div>
            </a>

            <a href="{{ route('empresas.index') }}" style="text-decoration:none;">
                <div style="border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center;transition:all .15s;" onmouseover="this.style.borderColor='var(--accent)';this.style.background='var(--blue-bg)'" onmouseout="this.style.borderColor='var(--border)';this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:var(--green-bg);border:1px solid var(--green-bd);border-radius:8px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--green);fill:none;stroke-width:2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div style="font-size:12px;font-weight:600;color:var(--text)">Empresas</div>
                </div>
            </a>

            <a href="{{ route('terceros.index') }}" style="text-decoration:none;">
                <div style="border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center;transition:all .15s;" onmouseover="this.style.borderColor='var(--accent)';this.style.background='var(--blue-bg)'" onmouseout="this.style.borderColor='var(--border)';this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:8px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:var(--text2);fill:none;stroke-width:2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div style="font-size:12px;font-weight:600;color:var(--text)">Terceros</div>
                </div>
            </a>

        </div>
    </div>

</x-app-layout>
