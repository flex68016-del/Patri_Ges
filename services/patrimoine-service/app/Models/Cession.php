<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Acte de cession d'un bien : vente, don ou destruction.
 * Conserve la date, le type et la valeur eventuelle de la transaction,
 * ainsi que l'administrateur qui a valide l'acte.
 */
class Cession extends Model
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
        'valeur_cession',
        'valide_par_id',
    ];

    protected $casts = [
        'date' => 'date',
        'valeur_cession' => 'decimal:2',
    ];

    /** Bien ayant fait l'objet de la cession. */
    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}
