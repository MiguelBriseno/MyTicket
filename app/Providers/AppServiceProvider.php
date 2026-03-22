<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Ticket::class => TicketPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin puede hacer todo sin restricciones
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
