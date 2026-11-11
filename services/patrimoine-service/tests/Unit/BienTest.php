<?php

namespace Tests\Unit;

use App\Models\Bien;
use App\Models\Categorie;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BienTest extends TestCase
{
    use RefreshDatabase;

    public function test_bien_can_be_created()
    {
        $bien = Bien::factory()->create([
            'code' => 'IMM000001',
            'designation' => 'Bureau administratif',
        ]);

        $this->assertDatabaseHas('biens', [
            'code' => 'IMM000001',
            'designation' => 'Bureau administratif',
        ]);

        $this->assertInstanceOf(Bien::class, $bien);
    }

    public function test_bien_belongs_to_categorie()
    {
        $categorie = Categorie::factory()->create();
        $bien = Bien::factory()->create(['categorie_id' => $categorie->id]);

        $this->assertEquals($categorie->id, $bien->categorie->id);
    }

    public function test_bien_belongs_to_service()
    {
        $service = Service::factory()->create();
        $bien = Bien::factory()->create(['service_id' => $service->id]);

        $this->assertEquals($service->id, $bien->service->id);
    }

    public function test_bien_uses_uuid_as_primary_key()
    {
        $bien = Bien::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $bien->id
        );
    }

    public function test_calculer_valeur_net_comptable()
    {
        $categorie = Categorie::factory()->create(['duree_vie' => 10]);
        $bien = Bien::factory()->create([
            'categorie_id' => $categorie->id,
            'date_acquisition' => now()->subYears(3),
            'valeur_acquisition' => 100000,
        ]);

        $vnc = $bien->calculerValeurNetComptable();

        // VNC doit être un nombre
        $this->assertIsFloat($vnc);
        // VNC doit être inférieur ou égal à la valeur d'acquisition
        $this->assertLessThanOrEqual($bien->valeur_acquisition, $vnc);
    }

    public function test_calculer_vnc_ne_goes_below_zero()
    {
        $categorie = Categorie::factory()->create(['duree_vie' => 5]);
        $bien = Bien::factory()->create([
            'categorie_id' => $categorie->id,
            'date_acquisition' => now()->subYears(20),
            'valeur_acquisition' => 100000,
        ]);

        $vnc = $bien->calculerValeurNetComptable();

        // Au-delà de la durée de vie, VNC ne doit pas être négatif
        $this->assertGreaterThanOrEqual(0, $vnc);
    }
}
