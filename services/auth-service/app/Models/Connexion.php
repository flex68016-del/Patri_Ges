<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Connexion extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'utilisateur_id',
        'ip_address',
        'user_agent',
        'connecte_at',
        'deconnecte_at',
    ];

    protected $casts = [
        'connecte_at' => 'datetime',
        'deconnecte_at' => 'datetime',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
