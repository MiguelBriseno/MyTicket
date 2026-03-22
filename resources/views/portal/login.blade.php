<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Soporte — Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="bg-white rounded-xl border border-gray-200 p-8 w-full max-w-md">
    <h1 class="text-2xl font-semibold text-gray-800 text-center mb-2">Portal de Soporte</h1>
    <p class="text-sm text-gray-500 text-center mb-8">Inicia sesión para ver tus tickets</p>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('portal.login.submit') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Correo electrónico
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña
            </label>
            <input type="password" name="password" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit"
                class="w-full bg-amber-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-amber-600">
            Iniciar sesión
        </button>
    </form>

    <p class="text-center text-xs text-gray-400 mt-6">
        ¿Eres agente o admin?
        <a href="/admin/login" class="text-amber-500 hover:underline">Entra aquí</a>
    </p>
</div>
</body>
</html>
