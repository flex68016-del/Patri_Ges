<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\Bien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_can_be_created()
    {
        $service = Service::factory()->create([
            'code' => 'MIN001',
            'nom' => 'Ministère de la Santé',
        ]);

        $this->assertDatabaseHas('services', [
            'code' => 'MIN001',
            'nom' => 'Ministère de la Santé',
        ]);

        $this->assertInstanceOf(Service::class, $service);
    }

    public function test_service_has_many_biens()
    {
        $service = Service::factory()->create();
        $bien = Bien::factory()->create(['service_id' => $service->id]);

        $this->assertCount(1, $service->biens);
        $this->assertEquals($bien->id, $service->biens->first()->id);
    }

    public function test_service_uses_uuid_as_primary_key()
    {
        $service = Service::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $service->id
        );
    }

    public function test_service_default_actif_is_true()
    {
        $service = Service::factory()->create();

        $this->assertTrue($service->actif);
    }
}
