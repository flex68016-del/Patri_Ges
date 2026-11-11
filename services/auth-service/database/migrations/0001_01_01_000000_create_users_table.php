<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->enum('role', ['administrateur', 'gestionnaire', 'responsable', 'inspecteur']);
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('derniere_connexion_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('role');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
