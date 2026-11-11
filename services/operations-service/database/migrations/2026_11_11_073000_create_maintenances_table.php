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
            $table->enum('type', ['preventive', 'corrective']);
            $table->text('description')->nullable();
            $table->date('date_planifiee');
            $table->date('date_effective')->nullable();
            $table->decimal('cout', 15, 2)->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'annule']);
            $table->string('technicien')->nullable();
            $table->uuid('auteur_id');
            $table->timestamps();

            $table->index('bien_id');
            $table->index('statut');
            $table->index('date_planifiee');
            $table->index('auteur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
