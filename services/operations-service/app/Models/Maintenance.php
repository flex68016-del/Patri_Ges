<?php

namespace App\Models;

use Database\Factories\MaintenanceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Maintenance extends Model
{
    /** @use HasFactory<MaintenanceFactory> */
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
        'type',
        'description',
        'date_planifiee',
        'date_effective',
        'cout',
        'statut',
        'technicien',
        'auteur_id',
    ];

    protected $casts = [
        'date_planifiee' => 'date',
        'date_effective' => 'date',
        'cout' => 'decimal:2',
    ];
}
