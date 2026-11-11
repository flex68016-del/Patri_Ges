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
            $table->string('bien_libelle', 255);
            $table->enum('type', ['panne', 'degradation', 'perte']);
            $table->text('description');
            $table->enum('statut', ['signalee', 'en_cours', 'resolue']);
            $table->uuid('declare_par_id');
            $table->timestamps();

            $table->index('bien_id');
            $table->index('statut');
            $table->index('declare_par_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anomalies');
    }
};
