<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'service_id',
        'date_debut',
        'date_fin',
        'motif',
        'reference_acte',
        'auteur_id',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
