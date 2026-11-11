<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campagne_inventaires', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'annule']);
            $table->uuid('inspecteur_id');
            $table->uuid('auteur_id');
            $table->timestamps();

            $table->index('statut');
            $table->index(['date_debut', 'date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_inventaires');
    }
};
