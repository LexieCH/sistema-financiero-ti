<x-app-layout>
<x-slot name="header">
    <span class="text-gray-800 font-semibold">Empresas</span>
</x-slot>

<div class="max-w-7xl mx-auto">

    <!-- TITULO -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Gestión de Empresas
        </h1>
        <p class="text-sm text-gray-500">
            Administra las empresas registradas en el sistema multiempresa
        </p>
    </div>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200">

        <div class="flex justify-between items-center px-6 py-5 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Listado general
            </h2>

            <button onclick="abrirModal()" 
            class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
            + Nueva empresa
            </button>
        </div>

        <!-- TABLA -->
        <div class="p-6">
            <div class="overflow-hidden rounded-xl border">
                <table id="tablaEmpresas" class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Empresa</th>
                        <th class="px-4 py-3 text-left">RUT</th>
                        <th class="px-4 py-3 text-left">Giro</th>   
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">
                        @foreach($empresas ?? [] as $empresa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $empresa->id }}</td>

                            <td class="px-4 py-3 font-semibold text-gray-800">
                                {{ $empresa->nombre_fantasia }}
                            </td>

                            <td class="px-4 py-3">{{ $empresa->rut_empresa }}</td>

                            <td class="px-4 py-3 max-w-xs break-words">
                                {{ $empresa->giro }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    {{ $empresa->estado }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right space-x-3">
                                <button class="text-gray-700 hover:text-black">Editar</button>
                                <button class="text-red-600 hover:text-red-800">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</div>

@include('empresas.modal-create')
@include('empresas.scripts')

</x-app-layout>