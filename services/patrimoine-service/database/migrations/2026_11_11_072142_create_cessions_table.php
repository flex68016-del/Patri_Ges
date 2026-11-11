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
        Schema::create('cessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bien_id')->unique();
            $table->enum('type', ['vente', 'don', 'destruction']);
            $table->date('date');
            $table->decimal('valeur_cession', 15, 2)->nullable();
            $table->uuid('valide_par_id');
            $table->timestamps();

            $table->foreign('bien_id')->references('id')->on('biens');
            $table->index('bien_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cessions');
    }
};
