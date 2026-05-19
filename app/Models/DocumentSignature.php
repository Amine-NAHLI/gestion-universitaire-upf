<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentSignature extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'document_type',
        'data_hash',
        'signature',
        'sealed_data',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'sealed_data' => 'array',
            'issued_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
