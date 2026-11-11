<?php

namespace App\Models;

use Database\Factories\AnomalieFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Anomalie extends Model
{
    /** @use HasFactory<AnomalieFactory> */
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
        'gravite',
        'statut',
        'date_constatation',
        'date_resolution',
        'auteur_id',
    ];

    protected $casts = [
        'date_constatation' => 'date',
        'date_resolution' => 'date',
    ];
}
