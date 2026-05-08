<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Absence extends Model
{
    /** @use HasFactory<\Database\Factories\AbsenceFactory> */
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'seance_id',
        'justifiee',
        'date_creation'
    ];

    protected $casts = [
        'justifiee' => 'boolean',
        'date_creation' => 'datetime',
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class);
    }

    public function justificatif(): HasOne
    {
        return $this->hasOne(Justificatif::class);
    }
}
