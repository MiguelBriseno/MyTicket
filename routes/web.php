<?php

use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('portal.tickets');
});

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

        if (Auth::user()->hasAnyRole(['admin', 'agent'])) {
            return redirect('/admin');
        }

        return redirect()->route('portal.tickets');
    }

    return back()->with('error', 'Las credenciales no son correctas.');
})->name('portal.login.submit')->middleware('guest');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [PortalController::class, 'index']);
    Route::get('/tickets', [PortalController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/create', [PortalController::class, 'create'])->name('create');
    Route::get('/tickets/{ticket}', [PortalController::class, 'show'])->name('show');
});
