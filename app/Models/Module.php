<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'coefficient',
        'heures_cours',
        'heures_td',
        'heures_tp',
        'niveau_id',
        'semestre'
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'heures_cours' => 'integer',
        'heures_td' => 'integer',
        'heures_tp' => 'integer',
        'semestre' => 'integer',
    ];

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function professeurs(): BelongsToMany
    {
        return $this->belongsToMany(Professeur::class, 'module_professeur')
            ->withPivot('annee_universitaire')
            ->withTimestamps();
    }

    public function groupes(): BelongsToMany
    {
        return $this->belongsToMany(Groupe::class, 'module_groupe')
            ->withPivot('annee_universitaire')
            ->withTimestamps();
    }

    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function supportsCours(): HasMany
    {
        return $this->hasMany(SupportCours::class);
    }

    public function annonces(): HasMany
    {
        return $this->hasMany(Annonce::class);
    }
}
