<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BienEquipement extends Model
{
    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'numero_serie',
        'marque',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
