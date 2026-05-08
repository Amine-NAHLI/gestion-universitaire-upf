<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationApp extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationAppFactory> */
    use HasFactory;

    protected $table = 'notifications_app';

    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'type',
        'lien',
        'lue',
        'date_lecture'
    ];

    protected $casts = [
        'lue' => 'boolean',
        'date_lecture' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
