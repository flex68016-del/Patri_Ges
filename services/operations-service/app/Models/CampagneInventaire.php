<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampagneInventaire extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'service_id',
        'periode_debut',
        'periode_fin',
        'statut',
        'inspecteur_id',
    ];

    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin' => 'date',
    ];

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneInventaire::class, 'campagne_id');
    }
}
