<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bien_id');
            $table->string('bien_libelle', 255);
            $table->enum('type', ['preventive', 'corrective']);
            $table->enum('statut', ['planifiee', 'en_cours', 'cloturee', 'annulee']);
            $table->date('date_prevue');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->decimal('cout', 15, 2)->nullable();
            $table->string('prestataire', 255)->nullable();
            $table->text('description')->nullable();
            $table->uuid('auteur_id');
            $table->timestamps();

            $table->index('bien_id');
            $table->index('statut');
            $table->index('date_prevue');
            $table->index('auteur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
