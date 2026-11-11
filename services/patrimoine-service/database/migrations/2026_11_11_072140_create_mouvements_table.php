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
        Schema::create('mouvements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bien_id');
            $table->enum('type', ['creation', 'affectation', 'transfert', 'maintenance_debut', 'maintenance_fin', 'reforme', 'cession']);
            $table->timestamp('date')->useCurrent();
            $table->uuid('auteur_id');
            $table->json('detail')->nullable();
            $table->timestamps();

            $table->foreign('bien_id')->references('id')->on('biens');
            $table->index('bien_id');
            $table->index('type');
            $table->index('date');
            $table->index('auteur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements');
    }
};
