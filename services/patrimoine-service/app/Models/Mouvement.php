<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Mouvement de patrimoine : journal d'audit immuable tracant chaque
 * evenement du cycle de vie d'un bien (creation, affectation, maintenance,
 * reforme, cession...). Le champ detail (JSON) stocke le contexte
 * specifique de chaque evenement.
 */
class Mouvement extends Model
{
    protected $keyType = 'uuid';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

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

    /** Bien concerne par ce mouvement. */
    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
