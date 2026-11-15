<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Specialisation "vehicule" d'un bien : immatriculation, marque,
 * modele et kilometrage. Reliee au bien par bien_id.
 */
class BienVehicule extends Model
{
    // Nom de table explicite : evite la devinette Eloquent sur le pluriel.
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
