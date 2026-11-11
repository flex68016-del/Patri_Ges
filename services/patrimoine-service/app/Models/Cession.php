<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cession extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'type',
        'date',
        'valeur_cession',
        'valide_par_id',
    ];

    protected $casts = [
        'date' => 'date',
        'valeur_cession' => 'decimal:2',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
