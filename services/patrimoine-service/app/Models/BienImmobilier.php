<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Specialisation "immobilier" d'un bien : adresse, superficie,
 * titre foncier et coordonnees GPS. Reliee au bien par bien_id.
 */
class BienImmobilier extends Model
{
    // Nom de table explicite pour la meme raison que BienEquipement.
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
