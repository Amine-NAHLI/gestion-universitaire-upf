<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Niveau extends Model
{
    /** @use HasFactory<\Database\Factories\NiveauFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'numero', 'filiere_id'];

    protected $casts = [
        'numero' => 'integer',
    ];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function groupes(): HasMany
    {
        return $this->hasMany(Groupe::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
}
