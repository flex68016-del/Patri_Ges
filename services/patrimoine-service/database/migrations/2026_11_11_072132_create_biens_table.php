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
        Schema::create('biens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code_inventaire', 50)->unique();
            $table->string('designation', 255);
            $table->uuid('categorie_id');
            $table->date('date_acquisition');
            $table->enum('mode_acquisition', ['achat', 'don', 'transfert', 'construction']);
            $table->decimal('valeur_acquisition', 15, 2);
            $table->enum('etat', ['neuf', 'bon', 'moyen', 'degrade', 'hors_service']);
            $table->enum('statut', ['en_stock', 'affecte', 'en_maintenance', 'reforme', 'cede']);
            $table->uuid('service_courant_id')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->foreign('categorie_id')->references('id')->on('categories');
            $table->foreign('service_courant_id')->references('id')->on('services');
            $table->index('code_inventaire');
            $table->index('categorie_id');
            $table->index('service_courant_id');
            $table->index('etat');
            $table->index('statut');
            $table->index('date_acquisition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
