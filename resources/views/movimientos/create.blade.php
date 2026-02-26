<x-app-layout>
<x-slot name="header">
    <span class="text-gray-800 font-semibold">Nuevo Movimiento</span>
</x-slot>

<div class="max-w-4xl mx-auto">

    <!-- TITULO -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Registrar movimiento financiero
        </h1>
        <p class="text-sm text-gray-500">
            Ingresa ingresos o egresos del sistema
        </p>
    </div>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">

        <form method="POST" action="{{ route('movimientos.store') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4">

                <!-- tipo -->
                <div>
                    <label class="text-sm">Tipo movimiento</label>
                    <select name="tipo_movimiento_id" class="w-full border rounded px-3 py-2 mt-1" required>
                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- categoria -->
                <div>
                    <label class="text-sm">Categoría</label>
                    <select name="categoria_id" class="w-full border rounded px-3 py-2 mt-1" required>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- metodo -->
                <div>
                    <label class="text-sm">Método pago</label>
                    <select name="metodo_pago_id" class="w-full border rounded px-3 py-2 mt-1">
                        @foreach($metodos as $metodo)
                        <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- tercero -->
                <div>
                    <label class="text-sm">Cliente / Proveedor</label>
                    <select name="tercero_id" class="w-full border rounded px-3 py-2 mt-1">
                        <option value="">Opcional</option>
                        @foreach($terceros as $tercero)
                        <option value="{{ $tercero->id }}">{{ $tercero->razon_social }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- monto -->
                <div>
                    <label class="text-sm">Monto</label>
                    <input type="number" step="0.01" name="monto" class="w-full border rounded px-3 py-2 mt-1" required>
                </div>

                <!-- fecha -->
                <div>
                    <label class="text-sm">Fecha</label>
                    <input type="datetime-local" name="fecha"max="{{ now()->format('Y-m-d\TH:i') }}" onchange="this.blur()" class="w-full border rounded px-3 py-2 mt-1"
required>
                </div>

                <!-- descripción -->
                <div class="col-span-2">
                    <label class="text-sm">Descripción</label>
                    <textarea name="descripcion" class="w-full border rounded px-3 py-2 mt-1"></textarea>
                </div>

            </div>

            <div class="mt-6 text-right">
                <button class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800">
                    Guardar movimiento
                </button>
            </div>

        </form>

    </div>

</div>
</x-app-layout>