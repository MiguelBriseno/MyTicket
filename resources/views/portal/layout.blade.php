<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Soporte</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="max-w-4xl mx-auto flex items-center justify-between">
        <span class="font-semibold text-gray-800">Portal de Soporte</span>
        <div class="flex items-center gap-4">
            <a href="{{ route('portal.tickets') }}"
               class="text-sm text-gray-600 hover:text-gray-900">
                Mis tickets
            </a>
            <a href="{{ route('portal.create') }}"
               class="text-sm bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600">
                Nuevo ticket
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                    Salir
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="max-w-4xl mx-auto py-8 px-6">
    <div class="mb-6">
        <p class="text-sm text-gray-500">Hola, {{ auth()->user()->name }}</p>
    </div>
    @yield('content')
</main>

@livewireScripts
</body>
</html>
