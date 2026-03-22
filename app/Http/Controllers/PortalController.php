<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function index()
    {
        return redirect()->route('portal.tickets');
    }

    public function tickets()
    {
        return view('portal.tickets');
    }

    public function create()
    {
        return view('portal.create');
    }

    public function show(Ticket $ticket)
    {
        // Verificar que el ticket pertenece al usuario
        abort_if($ticket->created_by !== Auth::id(), 403);

        return view('portal.show', compact('ticket'));
    }
}
