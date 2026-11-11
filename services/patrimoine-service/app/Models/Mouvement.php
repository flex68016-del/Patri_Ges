<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mouvement extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'type',
        'date',
        'auteur_id',
        'detail',
    ];

    protected $casts = [
        'date' => 'datetime',
        'detail' => 'array',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
