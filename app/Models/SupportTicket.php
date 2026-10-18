<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'subject',
        'category',
        'priority',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (SupportTicket $ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'SUP-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'support_ticket_id');
    }
}
