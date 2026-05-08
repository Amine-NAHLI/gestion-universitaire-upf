<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportCours extends Model
{
    /** @use HasFactory<\Database\Factories\SupportCoursFactory> */
    use HasFactory;

    protected $table = 'supports_cours';

    protected $fillable = [
        'module_id',
        'professeur_id',
        'titre',
        'description',
        'fichier',
        'type_fichier',
        'taille'
    ];

    protected $casts = [
        'taille' => 'integer',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }
}
