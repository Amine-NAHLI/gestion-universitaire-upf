<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeAdministrative extends Model
{
    /** @use HasFactory<\Database\Factories\DemandeAdministrativeFactory> */
    use HasFactory;

    protected $table = 'demandes_administratives';

    protected $fillable = [
        'user_id',
        'type',
        'donnees_supplementaires',
        'statut',
        'motif_refus',
        'fichier_pdf',
        'validateur_id',
        'date_validation'
    ];

    protected $casts = [
        'donnees_supplementaires' => 'array',
        'date_validation' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }
}
