<?php

namespace Tests\Unit;

use App\Models\CampagneInventaire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampagneInventaireTest extends TestCase
{
    use RefreshDatabase;

    public function test_campagne_inventaire_can_be_created()
    {
        $campagne = CampagneInventaire::factory()->create([
            'titre' => 'Inventaire 2026',
            'statut' => 'en_cours',
        ]);

        $this->assertInstanceOf(CampagneInventaire::class, $campagne);
        $this->assertEquals('Inventaire 2026', $campagne->titre);
        $this->assertEquals('en_cours', $campagne->statut);
    }

    public function test_campagne_inventaire_uses_uuid_as_primary_key()
    {
        $campagne = CampagneInventaire::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $campagne->id
        );
    }

    public function test_campagne_inventaire_statut_enum()
    {
        $campagne = CampagneInventaire::factory()->create(['statut' => 'termine']);

        $this->assertEquals('termine', $campagne->statut);
    }
}
