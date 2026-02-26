<x-app-layout>
<x-slot name="header">
    <span class="text-gray-800 font-semibold">Movimientos</span>
</x-slot>

<div class="max-w-6xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Listado movimientos</h1>

    <a href="{{ route('movimientos.create') }}" class="bg-black text-white px-4 py-2 rounded mb-4 inline-block">
+ Nuevo movimiento
    </a>
    <div class="flex gap-6 mb-6">

        <div class="bg-white border rounded-xl px-6 py-4 shadow-sm">
            <p class="text-sm text-gray-500">Ingresos hoy</p>
            <p class="text-xl font-bold text-green-600">
            ${{ number_format($ingresosHoy,0,',','.') }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-6 py-4 shadow-sm">
            <p class="text-sm text-gray-500">Egresos hoy</p>
            <p class="text-xl font-bold text-red-600">
            ${{ number_format($egresosHoy,0,',','.') }}
            </p>
        </div>

        <div class="bg-white border rounded-xl px-6 py-4 shadow-sm">
            <p class="text-sm text-gray-500">Saldo hoy</p>
            <p class="text-xl font-bold">
            ${{ number_format($saldoHoy,0,',','.') }}
            </p>
        </div>

    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">ID</th>
                    <th class="text-left py-2">Tipo</th>
                    <th class="text-left py-2">Categoría</th>
                    <th class="text-left py-2">Descripción</th>
                    <th class="text-left py-2">Monto</th>
                    <th class="text-left py-2">Fecha</th>
                    <th class="text-left py-2">Usuario</th>
                </tr>
            </thead>
            <tbody>
            @foreach($movimientos as $m)
            <tr class="border-b">
                <td>{{ $m->id }}</td>

                <td>{{ $m->tipoMovimiento->nombre ?? '-' }}</td>

                <td>{{ $m->categoria->nombre ?? '-' }}</td>

                <td>{{ $m->descripcion ?? '-' }}</td>

                <td class="font-semibold text-green-700">
                    ${{ number_format($m->monto,0,',','.') }}
                </td>

                <td>{{ \Carbon\Carbon::parse($m->fecha)->format('d-m-Y H:i') }}</td>

                <td>{{ $m->usuario->name ?? '-' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>