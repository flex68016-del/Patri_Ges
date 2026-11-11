<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BienImmobilier extends Model
{
    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'adresse',
        'superficie',
        'titre_foncier',
        'coordonnees_gps',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
