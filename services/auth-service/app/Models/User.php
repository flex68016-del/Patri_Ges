<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'email',
        'password_hash',
        'nom',
        'role',
        'service_id',
        'actif',
        'email_verified_at',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'actif' => 'boolean',
        ];

    public function connexions(): HasMany
    {
        return $this->hasMany(Connexion::class, 'utilisateur_id');
    }
}
