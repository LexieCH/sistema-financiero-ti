<x-app-layout>

    <x-slot name="header">
        Movimientos
    </x-slot>


    <x-slot name="topbarAction">
        <a href="{{ route('movimientos.create') }}" class="btn-primary">

            <svg viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>

            Nuevo movimiento

        </a>
    </x-slot>


    {{-- KPI --}}
    <div class="kpi-grid kpi-3">

        {{-- INGRESOS --}}
        <div class="kpi-card">

            <div class="kpi-top">

                <div class="kpi-icon green">
                    <svg viewBox="0 0 24 24">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                </div>

                <span class="kpi-trend up">
                    <svg viewBox="0 0 24 24">
                        <polyline points="18 15 12 9 6 15"/>
                    </svg>
                    Hoy
                </span>

            </div>

            <div class="kpi-body">

                <div class="kpi-label">
                    Ingresos del día
                </div>

                <div class="kpi-value green">
                    ${{ number_format($ingresosHoy,0,',','.') }}
                </div>

                <div class="kpi-sub">
                    Movimientos de ingreso
                </div>

            </div>

        </div>


        {{-- EGRESOS --}}
        <div class="kpi-card">

            <div class="kpi-top">

                <div class="kpi-icon red">
                    <svg viewBox="0 0 24 24">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
                        <polyline points="17 18 23 18 23 12"/>
                    </svg>
                </div>

                <span class="kpi-trend down">
                    <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                    Hoy
                </span>

            </div>

            <div class="kpi-body">

                <div class="kpi-label">
                    Egresos del día
                </div>

                <div class="kpi-value red">
                    ${{ number_format($egresosHoy,0,',','.') }}
                </div>

                <div class="kpi-sub">
                    Movimientos de egreso
                </div>

            </div>

        </div>


        {{-- SALDO --}}
        <div class="kpi-card">

            <div class="kpi-top">

                <div class="kpi-icon blue">
                    <svg viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                    </svg>
                </div>

                <span class="kpi-trend {{ $saldoHoy >= 0 ? 'up' : 'down' }}">
                    Neto
                </span>

            </div>

            <div class="kpi-body">

                <div class="kpi-label">
                    Saldo del día
                </div>

                <div class="kpi-value blue">
                    ${{ number_format($saldoHoy,0,',','.') }}
                </div>

                <div class="kpi-sub">
                    Balance neto
                </div>

            </div>

        </div>

    </div>

    {{-- CENTROS DE COSTO --}}
        <div class="card">

            <div class="card-title">
                Gastos por Centro de Costo
            </div>

            <table style="width:100%">

                <thead>
                    <tr>
                        <th>Centro de costo</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($gastosCentroCosto as $g)

                    <tr>
                        <td>
                            {{ $g->centroCosto->nombre ?? 'Sin centro' }}
                        </td>

                        <td>
                            ${{ number_format($g->total,0,',','.') }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="2" style="text-align:center;color:var(--muted)">
                            No hay gastos registrados por centro de costo
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>
    {{-- TABLA --}}
    <div class="card">

        {{-- TABS --}}
        <div class="tabs">

            <div class="tab active">Todos</div>
            <div class="tab">Ingresos</div>
            <div class="tab">Egresos</div>

        </div>


        {{-- CONTROLES --}}
        <div class="dt-controls">

            <span style="font-size:11px;color:var(--muted)">
                Listado de movimientos registrados
            </span>

            <div class="card-actions">

                <div class="search-wrap">

                    <svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>

                    <input
                        class="search-input"
                        id="search-mov"
                        placeholder="Buscar movimiento..."
                    >

                </div>

            </div>

        </div>


        <table id="tablaMovimientos" style="width:100%">

            <thead>

                <tr>
                    <th>N°</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>

            </thead>


            <tbody>

                @foreach($movimientos as $m)

                <tr>

                    <td class="id-cell">
                        #{{ str_pad($m->id,3,'0',STR_PAD_LEFT) }}
                    </td>


                    <td>

                        @if(strtolower($m->tipoMovimiento->nombre ?? '') === 'ingreso')

                            <span class="badge green">
                                <span class="badge-dot"></span>
                                Ingreso
                            </span>

                        @else

                            <span class="badge red">
                                <span class="badge-dot"></span>
                                Egreso
                            </span>

                        @endif

                    </td>


                    <td style="color:var(--text2)">
                        {{ $m->descripcion ?? '—' }}
                    </td>


                    <td class="amount {{ strtolower($m->tipoMovimiento->nombre ?? '') === 'ingreso' ? 'pos' : 'neg' }}">

                        {{ strtolower($m->tipoMovimiento->nombre ?? '') === 'ingreso' ? '+' : '-' }}

                        ${{ number_format($m->monto,0,',','.') }}

                    </td>


                    <td style="font-size:12px;color:var(--text2)">

                        {{ \Carbon\Carbon::parse($m->fecha)->format('d-m-Y H:i') }}

                    </td>


                    <td style="font-size:12px">

                        {{ $m->usuario->name ?? '—' }}

                    </td>


                    <td>

                        <button class="btn-sm">

                            <svg viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                            Ver

                        </button>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>


<script>

$(document).ready(function(){

    var dt = $('#tablaMovimientos').DataTable({

        pageLength:10,

        language:{
            paginate:{ previous:'←', next:'→' },
            info:'Mostrando _START_ – _END_ de _TOTAL_ registros',
            infoEmpty:'Sin registros',
            emptyTable:'No hay movimientos registrados',
        },

        dom:'tip',

        columnDefs:[
            { orderable:false, targets:-1 }
        ],

        order:[[4,'desc']] // ordenar por fecha

    });


    $('#search-mov').on('keyup', function(){

        dt.search(this.value).draw();

    });


    // filtro por tabs
    document.querySelectorAll('.tab').forEach(function(tab){

        tab.addEventListener('click',function(){

            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));

            this.classList.add('active');

            var val = this.textContent.trim();

            if(val === 'Ingresos') dt.column(1).search('Ingreso').draw();

            else if(val === 'Egresos') dt.column(1).search('Egreso').draw();

            else dt.column(1).search('').draw();

        });

    });

});

</script>


</x-app-layout>