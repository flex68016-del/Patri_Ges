<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BienVehicule extends Model
{
    protected $table = 'biens_vehicules';

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
        'immatriculation',
        'marque',
        'modele',
        'kilometrage',
    ];

    protected $casts = [
        'kilometrage' => 'integer',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
