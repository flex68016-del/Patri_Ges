<?php

namespace App\Models;

use Database\Factories\CategorieFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Categorie de bien : regroupe les biens par nature
 * (immobilier, vehicule, equipement). Porte la duree de vie moyenne
 * qui pilote le calcul d'amortissement et de la valeur nette comptable.
 */
class Categorie extends Model
{
    /** @use HasFactory<CategorieFactory> */
    use HasFactory;

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
        'code',
        'nom',
        'type_bien',
        'description',
        'duree_vie',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /** Biens rattaches a cette categorie. */
    public function biens(): HasMany
    {
        return $this->hasMany(Bien::class);
    }
}
