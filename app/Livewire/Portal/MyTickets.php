<?php

namespace App\Livewire\Portal;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyTickets extends Component
{
    public function render()
    {
        $tickets = Ticket::where('created_by', Auth::id())
            ->with(['department', 'assignee'])
            ->latest()
            ->get();

        return view('livewire.portal.my-tickets', compact('tickets'));
    }
}
