<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'role',
        'telephone',
        'adresse',
        'date_naissance',
        'photo',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_naissance' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfesseur(): bool
    {
        return $this->role === 'professeur';
    }

    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->prenom} {$this->name}";
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-avatar.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function etudiant(): HasOne
    {
        return $this->hasOne(Etudiant::class);
    }

    public function professeur(): HasOne
    {
        return $this->hasOne(Professeur::class);
    }

    public function demandesAdministratives(): HasMany
    {
        return $this->hasMany(DemandeAdministrative::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationApp::class);
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }
}
