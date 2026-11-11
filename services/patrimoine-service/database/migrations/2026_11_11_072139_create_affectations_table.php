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
        Schema::create('affectations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bien_id');
            $table->uuid('service_id');
            $table->uuid('auteur_id');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->text('motif')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->foreign('bien_id')->references('id')->on('biens');
            $table->foreign('service_id')->references('id')->on('services');
            $table->index('bien_id');
            $table->index('service_id');
            $table->index('date_debut');
            $table->index('auteur_id');
            $table->index('actif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
