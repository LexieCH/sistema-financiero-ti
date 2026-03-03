<x-app-layout>
<x-slot name="header">
    <span class="text-gray-800 font-semibold">Documentos</span>
</x-slot>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Pendiente -->
    <div class="p-6 bg-yellow-50 rounded-xl shadow border">
        <h5 class="text-gray-500 text-sm">Pendiente</h5>
        <p class="text-2xl font-bold text-yellow-700">
            $ {{ number_format($totalPendiente, 0, ',', '.') }}
        </p>
    </div>

    <!-- Pagado -->
    <div class="p-6 bg-green-50 rounded-xl shadow border">
        <h5 class="text-gray-500 text-sm">Pagado</h5>
        <p class="text-2xl font-bold text-green-700">
            $ {{ number_format($totalPagado, 0, ',', '.') }}
        </p>
    </div>

    <!-- Total -->
    <div class="p-6 bg-blue-50 rounded-xl shadow border">
        <h5 class="text-gray-500 text-sm">Total General</h5>
        <p class="text-2xl font-bold text-blue-700">
            $ {{ number_format($totalGeneral, 0, ',', '.') }}
        </p>
    </div>

</div>
<div class="max-w-4xl mx-auto p-6">

    <form method="POST" action="{{ route('documentos.store') }}" class="space-y-4">
        @csrf

        <div>
            <label>Tipo Documento</label>
            <select name="tipo_documento_id" class="w-full border rounded p-2">
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Fecha emisión</label>
            <input type="date" name="fecha_emision" class="w-full border rounded p-2">
        </div>

        <div>
            <label>Monto Neto</label>
            <input type="number" step="0.01" name="monto_neto" class="w-full border rounded p-2">
        </div>
        <div>
            <label>IVA (19%)</label>
            <input type="text" id="iva"
            class="w-full border rounded p-2 bg-gray-100"
            readonly>
        </div>

        <div>
            <label>Total</label>
            <input type="text" id="total"
            class="w-full border rounded p-2 bg-gray-100"
            readonly>
        </div>

        <button type="submit" class="bg-black text-white px-4 py-2 rounded mt-6">
            Guardar Documento
        </button>
    </form>

</div>
<div class="mt-10 bg-white rounded-xl shadow border p-6">

    <h3 class="text-lg font-semibold mb-4">Listado de Documentos</h3>

    <div class="overflow-x-auto">
        <table id="tablaDocumentos" class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($documentos as $doc)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $doc->id }}</td>
                    <td class="px-4 py-3">
                        {{ $doc->tipoDocumento->nombre ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $doc->fecha_emision }}
                    </td>
                    <td class="px-4 py-3 font-semibold">
                        $ {{ number_format($doc->total, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs
                            @if($doc->estado == 'pendiente') bg-yellow-100 text-yellow-700
                            @elseif($doc->estado == 'pagado') bg-green-100 text-green-700
                            @else bg-gray-200 text-gray-600 @endif">
                            {{ $doc->estado }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const netoInput = document.querySelector('input[name="monto_neto"]');
        const ivaInput = document.getElementById('iva');
        const totalInput = document.getElementById('total');

        netoInput.addEventListener('input', function () {

            let neto = parseFloat(this.value) || 0;

            let iva = Math.round(neto * 0.19);
            let total = neto + iva;

            ivaInput.value = iva.toLocaleString('es-CL');
            totalInput.value = total.toLocaleString('es-CL');

        });

});
    </script>
</x-app-layout>