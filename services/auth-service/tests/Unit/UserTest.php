<?php

namespace Tests\Unit;

use App\Models\Connexion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'nom' => 'Doe',
            'prenom' => 'John',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'nom' => 'Doe',
            'prenom' => 'John',
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create();

        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_user_has_many_connexions()
    {
        $user = User::factory()->create();
        $connexion = Connexion::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->connexions);
        $this->assertEquals($connexion->id, $user->connexions->first()->id);
    }

    public function test_user_uses_uuid_as_primary_key()
    {
        $user = User::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $user->id
        );
    }

    public function test_user_default_statut_is_actif()
    {
        $user = User::factory()->create();

        $this->assertEquals('actif', $user->statut);
    }

    public function test_user_casts()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'derniere_connexion_at' => now(),
        ]);

        $this->assertIsObject($user->email_verified_at);
        $this->assertIsObject($user->derniere_connexion_at);
    }
}
