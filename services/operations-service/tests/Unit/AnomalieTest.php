<?php

namespace Tests\Unit;

use App\Models\Anomalie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnomalieTest extends TestCase
{
    use RefreshDatabase;

    public function test_anomalie_can_be_created()
    {
        $anomalie = Anomalie::factory()->create([
            'type' => 'degradation',
            'gravite' => 'moyenne',
            'statut' => 'signale',
        ]);

        $this->assertDatabaseHas('anomalies', [
            'type' => 'degradation',
            'gravite' => 'moyenne',
            'statut' => 'signale',
        ]);

        $this->assertInstanceOf(Anomalie::class, $anomalie);
    }

    public function test_anomalie_uses_uuid_as_primary_key()
    {
        $anomalie = Anomalie::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $anomalie->id
        );
    }

    public function test_anomalie_type_enum()
    {
        $anomalie = Anomalie::factory()->create(['type' => 'disparition']);

        $this->assertEquals('disparition', $anomalie->type);
    }

    public function test_anomalie_gravite_enum()
    {
        $anomalie = Anomalie::factory()->create(['gravite' => 'critique']);

        $this->assertEquals('critique', $anomalie->gravite);
    }

    public function test_anomalie_statut_enum()
    {
        $anomalie = Anomalie::factory()->create(['statut' => 'resolu']);

        $this->assertEquals('resolu', $anomalie->statut);
    }
}
