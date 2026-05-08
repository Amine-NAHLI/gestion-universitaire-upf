<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    /** @use HasFactory<\Database\Factories\FiliereFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'code', 'description'];

    public function niveaux(): HasMany
    {
        return $this->hasMany(Niveau::class);
    }
}
