<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'libelle',
        'type_bien',
        'duree_amortissement',
        'amortissable',
    ];

    protected $casts = [
        'amortissable' => 'boolean',
    ];

    public function biens(): HasMany
    {
        return $this->hasMany(Bien::class);
    }
}
