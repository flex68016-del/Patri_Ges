<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anomalies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bien_id');
            $table->enum('type', ['disparition', 'degradation', 'inventaire_ecart', 'autre']);
            $table->text('description')->nullable();
            $table->enum('gravite', ['faible', 'moyenne', 'elevee', 'critique']);
            $table->enum('statut', ['signale', 'en_cours', 'resolu', 'clos']);
            $table->date('date_constatation');
            $table->date('date_resolution')->nullable();
            $table->uuid('declare_par_id');
            $table->timestamps();

            $table->index('bien_id');
            $table->index('statut');
            $table->index('auteur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anomalies');
    }
};
