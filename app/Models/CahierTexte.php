<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CahierTexte extends Model
{
    /** @use HasFactory<\Database\Factories\CahierTexteFactory> */
    use HasFactory;

    protected $fillable = ['seance_id', 'objectif', 'contenu', 'nature'];

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class);
    }
}
