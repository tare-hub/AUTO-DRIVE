<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'entity_type',
        'entity_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function log(string $action, string $description, ?Model $entity = null, ?User $user = null): self
    {
        $currentUser = $user ?? auth()->user();

        return self::create([
            'user_id' => $currentUser?->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity?->getKey(),
        ]);
    }
}
