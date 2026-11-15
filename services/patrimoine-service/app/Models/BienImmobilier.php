<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BienImmobilier extends Model
{
    protected $table = 'biens_immobiliers';

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
