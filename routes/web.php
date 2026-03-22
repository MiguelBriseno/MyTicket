<?php

use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Raíz redirige al portal
Route::get('/', function () {
    return redirect()->route('portal.tickets');
});

// Login del portal
Route::get('/login', function () {
    return view('portal.login');
})->name('login')->middleware('guest');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, true)) {
        $request->session()->regenerate();

        // Admin y agentes van al panel
        if (Auth::user()->hasAnyRole(['admin', 'agent'])) {
            return redirect('/admin');
        }

        // Usuarios normales van al portal
        return redirect()->route('portal.tickets');
    }

    return back()->with('error', 'Las credenciales no son correctas.');
})->name('portal.login.submit')->middleware('guest');

// Logout
Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Portal protegido
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [PortalController::class, 'index']);
    Route::get('/tickets', [PortalController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/create', [PortalController::class, 'create'])->name('create');
});
