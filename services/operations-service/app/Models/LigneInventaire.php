<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Ligne de constat d'inventaire : resultat du controle physique d'un
 * bien precis dans une campagne (present, absent, degrade), avec
 * observation libre et identification de l'agent ayant constate.
 */
class LigneInventaire extends Model
{
    // Nom de table explicite pour correspondre au schema migrations.
    protected $table = 'lignes_inventaire';

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
        'campagne_id',
        'bien_id',
        'bien_libelle',
        'constat',
        'observation',
        'constate_par_id',
        'constate_at',
    ];

    protected $casts = [
        'constate_at' => 'datetime',
    ];

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneInventaire::class, 'campagne_id');
    }
}
