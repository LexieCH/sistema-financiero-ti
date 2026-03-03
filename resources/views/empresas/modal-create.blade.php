<div id="modalEmpresa" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

        <h2 class="text-lg font-semibold mb-4">Nueva empresa</h2>

        <form method="POST" action="{{ route('empresas.store') }}">
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