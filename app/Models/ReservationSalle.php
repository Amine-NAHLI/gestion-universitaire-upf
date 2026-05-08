<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationSalle extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationSalleFactory> */
    use HasFactory;

    protected $table = 'reservations_salles';

    protected $fillable = [
        'salle_id',
        'professeur_id',
        'date',
        'heure_debut',
        'heure_fin',
        'motif',
        'statut'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }
}
