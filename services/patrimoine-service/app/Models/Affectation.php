<?php

namespace App\Models;

use Database\Factories\AffectationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Affectation extends Model
{
    /** @use HasFactory<AffectationFactory> */
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
        'bien_id',
        'service_id',
        'auteur_id',
        'date_debut',
        'date_fin',
        'motif',
        'actif',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
