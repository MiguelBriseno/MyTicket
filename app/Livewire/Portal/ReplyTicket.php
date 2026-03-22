<?php

namespace App\Livewire\Portal;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReplyTicket extends Component
{
    public Ticket $ticket;
    public string $body = '';

    protected $rules = [
        'body' => 'required|min:5',
    ];

    public function submit()
    {
        $this->validate();

        Comment::create([
            'ticket_id'   => $this->ticket->id,
            'user_id'     => Auth::id(),
            'body'        => $this->body,
            'is_internal' => false,
        ]);

        $this->body = '';
        $this->dispatch('commentAdded');
        $this->ticket->refresh();
    }

    public function render()
    {
        return view('livewire.portal.reply-ticket');
    }
}
