<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneInventaire extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'campagne_id',
        'bien_id',
        'bien_libelle',
        'constat',
        'observation',
        'constate_par_id',
        'constate_at',
    ];

    protected $casts = [
        'constate_at' => 'datetime',
    ];

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneInventaire::class, 'campagne_id');
    }
}
