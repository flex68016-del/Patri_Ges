<?php

namespace App\Models;

use Database\Factories\CampagneInventaireFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CampagneInventaire extends Model
{
    /** @use HasFactory<CampagneInventaireFactory> */
    use HasFactory;

    protected $table = 'campagne_inventaires';

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
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'statut',
        'inspecteur_id',
        'auteur_id',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneInventaire::class, 'campagne_id');
    }
}
