<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'department_id',
        'created_by',
        'assigned_to',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'application/pdf',
            ]);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->nonQueued();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    public function markAsResolved(): void
    {
        $this->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    protected static function booted(): void
    {
        static::updated(function (Ticket $ticket) {
            if ($ticket->wasChanged('assigned_to') && $ticket->assignee) {
                $ticket->assignee->notify(new \App\Notifications\TicketAssigned($ticket));
            }

            if ($ticket->wasChanged('status') && $ticket->status === 'resolved') {
                $ticket->creator->notify(new \App\Notifications\TicketResolved($ticket));
            }
        });
    }
}
