<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campagnes_inventaire', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('service_id');
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->enum('statut', ['ouverte', 'cloturee']);
            $table->uuid('inspecteur_id');
            $table->timestamps();

            $table->index('service_id');
            $table->index('statut');
            $table->index(['periode_debut', 'periode_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagnes_inventaire');
    }
};
