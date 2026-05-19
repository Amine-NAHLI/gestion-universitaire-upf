<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\DemandeAdministrative;

class Etudiant extends Model
{
    /** @use HasFactory<\Database\Factories\EtudiantFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cne',
        'matricule',
        'groupe_id',
        'date_inscription',
        'statut'
    ];

    protected $casts = [
        'date_inscription' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function justificatifs(): HasMany
    {
        return $this->hasMany(Justificatif::class);
    }

    /**
     * Accès aux demandes administratives de l'étudiant (via son User).
     */
    public function demandesAdministratives(): HasManyThrough
    {
        return $this->hasManyThrough(
            DemandeAdministrative::class,
            User::class,
            'id',       // FK sur users
            'user_id',  // FK sur demandes_administratives
            'user_id',  // local key sur etudiants
            'id'        // local key sur users
        );
    }

    public function getNomCompletAttribute(): string
    {
        return $this->user->prenom . ' ' . $this->user->name;
    }
}
