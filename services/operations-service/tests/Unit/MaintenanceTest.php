<?php

namespace Tests\Unit;

use App\Models\Maintenance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_maintenance_can_be_created()
    {
        $maintenance = Maintenance::factory()->create([
            'type' => 'preventive',
            'description' => 'Maintenance préventive mensuelle',
        ]);

        $this->assertDatabaseHas('maintenances', [
            'type' => 'preventive',
            'description' => 'Maintenance préventive mensuelle',
        ]);

        $this->assertInstanceOf(Maintenance::class, $maintenance);
    }

    public function test_maintenance_uses_uuid_as_primary_key()
    {
        $maintenance = Maintenance::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $maintenance->id
        );
    }

    public function test_maintenance_type_enum()
    {
        $maintenance = Maintenance::factory()->create(['type' => 'corrective']);

        $this->assertEquals('corrective', $maintenance->type);
    }

    public function test_maintenance_statut_enum()
    {
        $maintenance = Maintenance::factory()->create(['statut' => 'termine']);

        $this->assertEquals('termine', $maintenance->statut);
    }
}
