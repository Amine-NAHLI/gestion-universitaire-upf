<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Professeur extends Model
{
    /** @use HasFactory<\Database\Factories\ProfesseurFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'specialite',
        'grade',
        'date_recrutement'
    ];

    protected $casts = [
        'date_recrutement' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_professeur')
            ->withPivot('annee_universitaire')
            ->withTimestamps();
    }

    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class);
    }

    public function cahierTextes(): HasManyThrough
    {
        return $this->hasManyThrough(CahierTexte::class, Seance::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ReservationSalle::class);
    }

    public function supportsCours(): HasMany
    {
        return $this->hasMany(SupportCours::class);
    }

    public function annonces(): HasMany
    {
        return $this->hasMany(Annonce::class);
    }

    public function getNomCompletAttribute(): string
    {
        return $this->user->prenom . ' ' . $this->user->name;
    }
}
