<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'code',
        'nom',
        'type',
        'parent_id',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function enfants(): HasMany
    {
        return $this->hasMany(Service::class, 'parent_id');
    }

    public function biens(): HasMany
    {
        return $this->hasMany(Bien::class, 'service_courant_id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }
}
