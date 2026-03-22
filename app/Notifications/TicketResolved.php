<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketResolved extends Notification implements ShouldQueue
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
            ->subject("Ticket resuelto: #{$this->ticket->id}")
            ->greeting("Hola {$notifiable->name}")
            ->line("Tu ticket #{$this->ticket->id}: {$this->ticket->title} ha sido resuelto.")
            ->action('Ver ticket', url("/admin/tickets/{$this->ticket->id}"))
            ->line('Si el problema persiste puedes reabrir el ticket.');
    }
}
