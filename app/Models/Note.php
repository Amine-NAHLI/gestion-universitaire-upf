<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'module_id',
        'cc1',
        'cc2',
        'examen',
        'note_finale',
        'annee_universitaire'
    ];

    protected $casts = [
        'cc1' => 'decimal:2',
        'cc2' => 'decimal:2',
        'examen' => 'decimal:2',
        'note_finale' => 'decimal:2',
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($note) {
            $note->calculateFinalNote();
        });

        static::updating(function ($note) {
            $note->calculateFinalNote();
        });
    }

    public function calculateFinalNote()
    {
        // La note finale n'est calculée que quand les trois composantes sont saisies
        if ($this->cc1 !== null && $this->cc2 !== null && $this->examen !== null) {
            $this->note_finale = ((floatval($this->cc1) + floatval($this->cc2)) / 2) * 0.4
                               + floatval($this->examen) * 0.6;
        } else {
            $this->note_finale = null;
        }
    }
}
