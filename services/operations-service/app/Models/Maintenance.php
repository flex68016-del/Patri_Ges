<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'bien_libelle',
        'type',
        'statut',
        'date_prevue',
        'date_debut',
        'date_fin',
        'cout',
        'prestataire',
        'description',
        'auteur_id',
    ];

    protected $casts = [
        'date_prevue' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'cout' => 'decimal:2',
    ];
}
