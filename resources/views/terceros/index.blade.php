<x-app-layout>
<x-slot name="header">
    <span class="text-gray-800 font-semibold">Terceros</span>
</x-slot>

<div class="max-w-7xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Gestión de Terceros
        </h1>
        <p class="text-sm text-gray-500">
            Clientes y proveedores del sistema
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-200">

        <div class="flex justify-between items-center px-6 py-5 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Listado general
            </h2>

            <button onclick="abrirModal()" 
                class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
                + Nuevo tercero
            </button>
        </div>

        <div class="p-6">
            <div class="overflow-hidden rounded-xl border">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">ID</th>
                            <th class="px-4 py-3 text-left">Razón Social</th>
                            <th class="px-4 py-3 text-left">RUT</th>
                            <th class="px-4 py-3 text-left">Tipo</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($terceros as $tercero)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $tercero->id }}</td>
                            <td class="px-4 py-3 font-semibold">
                                {{ $tercero->razon_social }}
                            </td>
                            <td class="px-4 py-3">{{ $tercero->rut }}</td>
                            <td class="px-4 py-3 capitalize">
                                {{ $tercero->tipo }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    Activo
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</x-app-layout>