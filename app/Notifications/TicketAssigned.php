<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ticket asignado: #{$this->ticket->id}")
            ->greeting("Hola {$notifiable->name}")
            ->line("Se te ha asignado el ticket #{$this->ticket->id}: {$this->ticket->title}")
            ->line("Prioridad: {$this->ticket->priority}")
            ->action('Ver ticket', url("/admin/tickets/{$this->ticket->id}"))
            ->line('Por favor atiéndelo a la brevedad.');
    }
}
