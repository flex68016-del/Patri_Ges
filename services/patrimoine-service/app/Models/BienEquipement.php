<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Specialisation "equipement" d'un bien : numero de serie et marque.
 * Reliee au bien par bien_id (table biens_equipements).
 */
class BienEquipement extends Model
{
    // Nom de table explicite : la convention Eloquent pluraliserait
    // incorrectement "biens_equipements" en "bien_equipements".
    protected $table = 'biens_equipements';

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
        'numero_serie',
        'marque',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
