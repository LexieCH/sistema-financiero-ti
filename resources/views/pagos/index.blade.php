<x-app-layout>

    <x-slot name="header">
        Pagos
    </x-slot>


    {{-- KPI --}}
    <div class="kpi-grid kpi-3">

        <div class="kpi-card">
            <div class="kpi-body">
                <div class="kpi-label">
                    Pagos del mes
                </div>

                <div class="kpi-value green">
                    ${{ number_format($pagosMes, 0, ',', '.') }}
                </div>
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-body">
                <div class="kpi-label">
                    Total pagos
                </div>

                <div class="kpi-value blue">
                    {{ $cantidadPagos }}
                </div>
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-body">
                <div class="kpi-label">
                    Promedio pago
                </div>

                <div class="kpi-value yellow">
                    ${{ number_format($promedioPago, 0, ',', '.') }}
                </div>
            </div>
        </div>

    </div>


    {{-- TABLA --}}
    <div class="card">

        <div class="dt-controls">

            <div class="card-title">
                Historial de pagos
            </div>


            <div class="card-actions">

                <div class="search-wrap">

                    <input
                        type="text"
                        class="search-input"
                        id="search-pagos"
                        placeholder="Buscar pago..."
                    >

                </div>

            </div>

        </div>


        <table
            id="tablaPagos"
            class="dataTable no-footer"
            style="width:100%"
        >

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Documento</th>
                    <th>Total documento</th>
                    <th>Total pagado</th>
                    <th>Saldo</th>
                    <th>Monto pago</th>
                    <th>Fecha</th>
                    <th>Movimiento</th>
                </tr>

            </thead>


            <tbody>

                @foreach ($pagos as $pago)

                    <tr>

                        <td>
                            #{{ $pago->id }}
                        </td>


                        <td>
                            Documento #{{ $pago->documento_id }}
                        </td>


                        <td>
                            ${{ number_format($pago->documento->total ?? 0, 0, ',', '.') }}
                        </td>


                        <td>
                            ${{ number_format($pago->documento->totalPagado(), 0, ',', '.') }}
                        </td>


                        <td>
                            ${{ number_format($pago->documento->saldoPendiente(), 0, ',', '.') }}
                        </td>


                        <td>
                            ${{ number_format($pago->monto, 0, ',', '.') }}
                        </td>


                        <td>
                            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d-m-Y') }}
                        </td>

                        <td>
                            @php($movId = $movimientosPorReferencia['PAGO-' . $pago->id] ?? null)

                            @if($movId)
                                <a href="{{ route('movimientos.edit', $movId) }}" class="btn-sm">
                                    Ver movimiento
                                </a>
                            @else
                                <span class="badge yellow"><span class="badge-dot"></span>Sin movimiento</span>
                            @endif
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>



    <script>

        $(document).ready(function () {

            var dt = $('#tablaPagos').DataTable({

                pageLength: 10,

                language: {
                    paginate: {
                        previous: '←',
                        next: '→'
                    },
                    info: 'Mostrando _START_ – _END_ de _TOTAL_ registros',
                    infoEmpty: 'Sin registros',
                    emptyTable: 'No hay pagos registrados'
                },

                dom: 'tip',

                columnDefs: [
                    {
                        orderable: false,
                        targets: -1
                    }
                ]

            });


            $('#search-pagos').on('keyup', function () {
                dt.search(this.value).draw();
            });

        });

    </script>


</x-app-layout>