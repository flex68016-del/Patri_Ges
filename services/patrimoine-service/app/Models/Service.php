<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Service (ministere, direction, administration) : entite
 * organisationnelle qui recoit les affectations de biens et porte la
 * responsabilite metier sur le patrimoine confie.
 */
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
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
        'description',
        'responsable',
        'email',
        'telephone',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /** Biens actuellement ou historiquement rattaches au service. */
    public function biens(): HasMany
    {
        return $this->hasMany(Bien::class, 'service_id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }
}
