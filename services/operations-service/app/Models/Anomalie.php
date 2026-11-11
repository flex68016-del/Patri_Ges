<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anomalie extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'bien_libelle',
        'type',
        'description',
        'statut',
        'declare_par_id',
    ];
}
