<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'agent', 'user']);
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('agent')) {
            return $ticket->assigned_to === $user->id
                || $ticket->department_id === $user->department_id;
        }

        return $ticket->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'agent', 'user']);
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('agent')) {
            return $ticket->assigned_to === $user->id;
        }

        // usuario normal solo puede editar sus tickets abiertos
        return $ticket->created_by === $user->id
            && $ticket->status === 'open';
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }
}
