<x-app-layout>
<x-slot name="header">
    <span class="text-gray-800 font-semibold">Usuarios</span>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto">

    <!-- TITULO -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Gestión de Usuarios
        </h1>
        <p class="text-sm text-gray-500">
            Administra los usuarios del sistema
        </p>
    </div>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200">

        <!-- HEADER CARD -->
        <div class="flex justify-between items-center px-6 py-5 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Listado general
            </h2>

            <button onclick="abrirModal()" 
            class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
            + Nuevo usuario
            </button>
        </div>

        <!-- TABLA -->
        <div class="p-6">
            <div class="overflow-hidden rounded-xl border">
                <table id="tablaUsuarios" class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Empresa</th>
                        <th class="px-4 py-3 text-left">Rol</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">
                        @foreach($usuarios as $usuario)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $usuario->id }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $usuario->name }}</td>
                            <td class="px-4 py-3">{{ $usuario->email }}</td>
                            <td class="px-4 py-3">{{ $usuario->empresa->nombre_fantasia ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $usuario->rol->nombre ?? '-' }}</td>

                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    {{ $usuario->estado }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('usuarios.destroy',$usuario->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                        Desactivar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>

</div>
</div>
</x-app-layout>


<!-- DATATABLE -->
<script>
$(document).ready(function() {
    $('#tablaUsuarios').DataTable({
        pageLength: 5,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>


<!-- MODAL CREAR USUARIO -->
<div id="modalUsuario" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

<h2 class="text-lg font-semibold mb-4">Nuevo usuario</h2>

<form method="POST" action="{{ route('usuarios.store') }}">
@csrf

<div class="mb-3">
<label class="text-sm">Nombre</label>
<input type="text" name="name" class="w-full border rounded px-3 py-2">
</div>

<div class="mb-3">
<label class="text-sm">Email</label>
<input type="email" name="email" class="w-full border rounded px-3 py-2">
</div>

<div class="mb-3">
<label class="text-sm">Password</label>
<input type="password" name="password" class="w-full border rounded px-3 py-2">
</div>

<div class="mb-3">
<label class="text-sm">Empresa</label>
<select name="empresa_id" class="w-full border rounded px-3 py-2">
@foreach($empresas as $empresa)
<option value="{{ $empresa->id }}">{{ $empresa->nombre_fantasia }}</option>
@endforeach
</select>
</div>

<div class="mb-3">
<label class="text-sm">Rol</label>
<select name="rol_id" class="w-full border rounded px-3 py-2">
@foreach($roles as $rol)
<option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
@endforeach
</select>
</div>

<div class="flex justify-end gap-2 mt-5">
<button type="button" onclick="cerrarModal()" class="px-4 py-2 border rounded">
Cancelar
</button>

<button class="bg-black text-white px-4 py-2 rounded">
Guardar
</button>
</div>

</form>
</div>
</div>

<script>
function abrirModal(){
document.getElementById('modalUsuario').classList.remove('hidden');
document.getElementById('modalUsuario').classList.add('flex');
}
function cerrarModal(){
document.getElementById('modalUsuario').classList.add('hidden');
}
</script>