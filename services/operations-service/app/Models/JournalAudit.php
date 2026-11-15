<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Journal d'audit des operations : trace qui a fait quoi, sur quel
 * objet et quand (action, type d'objet, detail JSON). Table de
 * tracabilite transverse du service operations.
 */
class JournalAudit extends Model
{
    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $fillable = [
        'acteur_id',
        'action',
        'objet_type',
        'objet_id',
        'detail',
    ];

    protected $casts = [
        'detail' => 'array',
    ];
}
