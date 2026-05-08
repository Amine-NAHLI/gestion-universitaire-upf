<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Justificatif extends Model
{
    /** @use HasFactory<\Database\Factories\JustificatifFactory> */
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'absence_id',
        'fichier',
        'motif',
        'statut',
        'motif_refus',
        'date_soumission',
        'validateur_id'
    ];

    protected $casts = [
        'date_soumission' => 'datetime',
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function absence(): BelongsTo
    {
        return $this->belongsTo(Absence::class);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }
}
