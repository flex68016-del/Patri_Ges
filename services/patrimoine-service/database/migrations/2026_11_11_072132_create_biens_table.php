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
            $table->string('code', 50)->unique();
            $table->string('designation', 255);
            $table->uuid('categorie_id');
            $table->uuid('service_id')->nullable();
            $table->date('date_acquisition');
            $table->decimal('valeur_acquisition', 15, 2);
            $table->enum('statut', ['disponible', 'affecte', 'en_maintenance', 'reforme', 'cede']);
            $table->string('emplacement')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->foreign('categorie_id')->references('id')->on('categories');
            $table->foreign('service_id')->references('id')->on('services');
            $table->index('code');
            $table->index('categorie_id');
            $table->index('service_id');
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
