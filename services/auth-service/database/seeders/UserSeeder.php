<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@patrimoine.gouv',
                'password' => Hash::make('password123'),
                'nom' => 'Kouassi',
                'prenom' => 'Yao',
                'role' => 'administrateur',
                'statut' => 'actif',
                'email_verified_at' => now(),
            ],
            [
                'email' => 'gestionnaire@patrimoine.gouv',
                'password' => Hash::make('password123'),
                'nom' => 'Touré',
                'prenom' => 'Awa',
                'role' => 'gestionnaire',
                'statut' => 'actif',
                'email_verified_at' => now(),
            ],
            [
                'email' => 'responsable@patrimoine.gouv',
                'password' => Hash::make('password123'),
                'nom' => 'Diop',
                'prenom' => 'Moussa',
                'role' => 'responsable',
                'statut' => 'actif',
                'email_verified_at' => now(),
            ],
            [
                'email' => 'inspecteur@patrimoine.gouv',
                'password' => Hash::make('password123'),
                'nom' => 'Koné',
                'prenom' => 'Fatou',
                'role' => 'inspecteur',
                'statut' => 'actif',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
