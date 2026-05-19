<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seance extends Model
{
    /** @use HasFactory<\Database\Factories\SeanceFactory> */
    use HasFactory;

    protected $fillable = [
        'module_id',
        'professeur_id',
        'groupe_id',
        'salle_id',
        'date',
        'heure_debut',
        'heure_fin',
        'type',
        'statut'
    ];

    protected $casts = [
        'date' => 'date',
        // heure_debut and heure_fin are stored as TIME strings (HH:MM:SS)
        // We use Carbon::parse() manually when formatting is needed
    ];

    /**
     * Get heure_debut as a Carbon instance by parsing the time string.
     */
    public function getHeureDebutAttribute($value): ?\Carbon\Carbon
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    /**
     * Get heure_fin as a Carbon instance by parsing the time string.
     */
    public function getHeureFinAttribute($value): ?\Carbon\Carbon
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class);
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function cahierTexte(): HasOne
    {
        return $this->hasOne(CahierTexte::class);
    }
}
