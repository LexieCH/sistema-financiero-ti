<x-app-layout>
<x-slot name="header">
    <span class="text-gray-800 font-semibold">Empresas</span>
</x-slot>

<div class="max-w-7xl mx-auto">

    <!-- TITULO MODULO -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Gestión de Empresas
        </h1>
        <p class="text-sm text-gray-500">
            Administra las empresas registradas en el sistema multiempresa
        </p>
    </div>

    <!-- CARD PRINCIPAL -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200">

        <!-- HEADER CARD -->
        <div class="flex justify-between items-center px-6 py-5 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Listado general
            </h2>

            <button onclick="abrirModal()" 
            class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
            + Nueva empresa
            </button>
        </div>

        <!-- Tabla -->
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

                            <td class="px-4 py-3">{{ $empresa->giro }}</td>

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
</x-app-layout>
    <script>
    $(document).ready(function() {
        $('#tablaEmpresas').DataTable({
            pageLength: 5,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    });
    </script>
    <script>
    function abrirModal(){
        document.getElementById('modalEmpresa').classList.remove('hidden');
        document.getElementById('modalEmpresa').classList.add('flex');
    }

    function cerrarModal(){
        document.getElementById('modalEmpresa').classList.add('hidden');
    }
    </script>
    <!-- MODAL CREAR EMPRESA -->
    <div id="modalEmpresa" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

            <h2 class="text-lg font-semibold mb-4">Nueva empresa</h2>

            <form id="formEmpresa">
                @csrf

                <div class="mb-3">
                    <label class="text-sm">Nombre empresa</label>
                    <input type="text" name="nombre_fantasia" class="w-full border rounded px-3 py-2 mt-1">
                </div>

                <div class="mb-3">
                    <label class="text-sm">RUT</label>
                    <input type="text" name="rut_empresa" class="w-full border rounded px-3 py-2 mt-1">
                </div>

                <div class="mb-3">
                    <label class="text-sm">Razón social</label>
                    <input type="text" name="razon_social" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div class="mb-3">
                    <label class="text-sm">Giro</label>
                    <input type="text" name="giro" class="w-full border rounded px-3 py-2 mt-1">
                </div>

                <div class="flex justify-end gap-2 mt-5">
                    <button type="button" onclick="cerrarModal()" class="px-4 py-2 border rounded">
                        Cancelar
                    </button>

                    <button type="submit" class="bg-black text-white px-4 py-2 rounded">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>