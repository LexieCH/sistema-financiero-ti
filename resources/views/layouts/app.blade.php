<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema Financiero TI</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
        <div class="p-6 text-xl font-bold border-b border-gray-700">
            Sistema TI
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="/dashboard" class="block px-4 py-2 rounded hover:bg-gray-700">Dashboard</a>
            <a href="/empresas" class="block px-4 py-2 rounded hover:bg-gray-700">Empresas</a>
            <a href="/usuarios" class="block px-4 py-2 rounded hover:bg-gray-700">Usuarios</a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Roles</a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Movimientos</a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Documentos</a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Reportes</a>
        </nav>

        <div class="p-4 border-t border-gray-700 text-sm">
            {{ Auth::user()->name }} <br>
            <span class="text-gray-400">{{ Auth::user()->email }}</span>
        </div>
    </aside>

    <!-- CONTENIDO -->
    <div class="flex-1 flex flex-col">

        <!-- TOP BAR -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">
                {{ $header ?? 'Dashboard' }}
            </h1>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-600 text-sm">Cerrar sesión</button>
            </form>
        </header>

        <!-- PAGE -->
        <main class="p-6 flex-1">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>