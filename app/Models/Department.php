<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function agents()
    {
        return User::role('agent')
            ->whereHas('tickets', fn($q) => $q->where('department_id', $this->id))
            ->orWhereHas('assignedTickets', fn($q) => $q->where('department_id', $this->id));
    }
}
