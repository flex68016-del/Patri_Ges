<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bien extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'code_inventaire',
        'designation',
        'categorie_id',
        'date_acquisition',
        'mode_acquisition',
        'valeur_acquisition',
        'etat',
        'statut',
        'service_courant_id',
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

    public function serviceCourant(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_courant_id');
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

    public function getSpecialisation()
    {
        return match($this->categorie->type_bien) {
            'immobilier' => $this->immobilier,
            'vehicule' => $this->vehicule,
            'equipement' => $this->equipement,
            default => null,
        };
    }

    public function calculerValeurNetComptable(): float
    {
        if (!$this->categorie->amortissable) {
            return $this->valeur_acquisition;
        }

        $anneesDepuisAcquisition = now()->diffInYears($this->date_acquisition);
        $tauxAmortissement = 100 / $this->categorie->duree_amortissement;
        $cumulAmortissement = min($anneesDepuisAcquisition * $tauxAmortissement, 100);

        return $this->valeur_acquisition * (1 - $cumulAmortissement / 100);
    }
}
