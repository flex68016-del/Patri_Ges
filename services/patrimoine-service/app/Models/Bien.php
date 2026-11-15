<?php

namespace App\Models;

use Database\Factories\BienFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * Bien du patrimoine de l'Etat : entite centrale du systeme.
 * Chaque bien possede une categorie (qui porte la duree de vie pour
 * l'amortissement), un service responsable eventuel, une specialisation
 * selon son type (immobilier, vehicule, equipement) et un historique
 * complet trace dans la table mouvements.
 */
class Bien extends Model
{
    /** @use HasFactory<BienFactory> */
    use HasFactory;

    // Cle primaire UUID : identifiants opaques, sans sequence.
    protected $keyType = 'uuid';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        // Genere l'UUID a l'insertion si aucun id n'est fourni.
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

    /** Affectation en cours (sans date de fin), s'il y en a une. */
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

    /**
     * Retourne la fiche de specialisation correspondant au type de la
     * categorie (immobilier, vehicule ou equipement), ou null.
     */
    public function getSpecialisationAttribute()
    {
        if (! $this->categorie) {
            return null;
        }

        return match ($this->categorie->type_bien) {
            'immobilier' => $this->immobilier,
            'vehicule' => $this->vehicule,
            'equipement' => $this->equipement,
            default => null,
        };
    }

    /**
     * Calcule la valeur nette comptable (VNC) : valeur d'acquisition
     * amortie linearement sur la duree de vie de la categorie.
     * L'amortissement est plafonne a 100 % (la VNC ne devient jamais
     * negative) et le resultat est plancher a 0.
     */
    public function calculerValeurNetComptable(): float
    {
        if (! $this->categorie || ! $this->categorie->duree_vie) {
            return $this->valeur_acquisition;
        }

        $joursDepuisAcquisition = $this->date_acquisition->diffInDays(now());
        $joursDureeVie = $this->categorie->duree_vie * 365;
        // Taux d'amortissement cumule : proportion de la duree de vie ecoulee.
        $tauxAmortissement = $joursDepuisAcquisition / $joursDureeVie;
        $cumulAmortissement = min($tauxAmortissement * 100, 100);

        return max(0, $this->valeur_acquisition * (1 - $cumulAmortissement / 100));
    }
}
