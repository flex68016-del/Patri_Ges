<?php

namespace App\Models;

use Database\Factories\BienFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Bien extends Model
{
    /** @use HasFactory<BienFactory> */
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
        'designation',
        'categorie_id',
        'service_id',
        'date_acquisition',
        'valeur_acquisition',
        'statut',
        'emplacement',
        'actif',
    ];

    protected $casts = [
        'date_acquisition' => 'date',
        'valeur_acquisition' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function immobilier(): HasOne
    {
        return $this->hasOne(BienImmobilier::class);
    }

    public function vehicule(): HasOne
    {
        return $this->hasOne(BienVehicule::class);
    }

    public function equipement(): HasOne
    {
        return $this->hasOne(BienEquipement::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    public function affectationCourante(): HasOne
    {
        return $this->hasOne(Affectation::class)->whereNull('date_fin');
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class);
    }

    public function cession(): HasOne
    {
        return $this->hasOne(Cession::class);
    }

    public function getSpecialisationAttribute()
    {
        if (!$this->categorie) {
            return null;
        }

        return match($this->categorie->type_bien) {
            'immobilier' => $this->immobilier,
            'vehicule' => $this->vehicule,
            'equipement' => $this->equipement,
            default => null,
        };
    }

    public function calculerValeurNetComptable(): float
    {
        if (!$this->categorie || !$this->categorie->duree_vie) {
            return $this->valeur_acquisition;
        }

        $joursDepuisAcquisition = $this->date_acquisition->diffInDays(now());
        $joursDureeVie = $this->categorie->duree_vie * 365;
        $tauxAmortissement = $joursDepuisAcquisition / $joursDureeVie;
        $cumulAmortissement = min($tauxAmortissement * 100, 100);

        return max(0, $this->valeur_acquisition * (1 - $cumulAmortissement / 100));
    }
}
