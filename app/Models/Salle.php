<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    /** @use HasFactory<\Database\Factories\SalleFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'capacite', 'type', 'equipements', 'is_disponible'];

    protected $casts = [
        'capacite' => 'integer',
        'is_disponible' => 'boolean',
    ];

    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ReservationSalle::class);
    }
}
