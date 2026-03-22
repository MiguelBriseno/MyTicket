<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public Comment $comment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nuevo comentario en ticket #{$this->ticket->id}")
            ->greeting("Hola {$notifiable->name}")
            ->line("Hay un nuevo comentario en el ticket #{$this->ticket->id}: {$this->ticket->title}")
            ->line("\"{$this->comment->body}\"")
            ->action('Ver ticket', url("/admin/tickets/{$this->ticket->id}"))
            ->line('Ingresa al sistema para responder.');
    }
}
