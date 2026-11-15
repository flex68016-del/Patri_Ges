<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Compte utilisateur de la plateforme. L'identifiant est un UUID
 * (genere automatiquement a la creation) et le mot de passe est stocke
 * hache en bcrypt via le cast implicite de Laravel.
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Cle primaire UUID non incrementale, type string.
    protected $keyType = 'uuid';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        // Genere l'UUID cote application si aucun id n'est fourni,
        // coherent avec le schema PostgreSQL (colonne uuid).
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'email',
        'password',
        'nom',
        'prenom',
        'role',
        'statut',
        'email_verified_at',
        'derniere_connexion_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'derniere_connexion_at' => 'datetime',
        ];
    }

    /** Historique des sessions de connexion de l'utilisateur. */
    public function connexions(): HasMany
    {
        return $this->hasMany(Connexion::class, 'user_id');
    }
}
