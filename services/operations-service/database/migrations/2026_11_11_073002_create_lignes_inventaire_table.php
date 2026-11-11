<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignes_inventaire', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campagne_id');
            $table->uuid('bien_id');
            $table->string('bien_libelle', 255);
            $table->enum('constat', ['present', 'absent', 'degrade']);
            $table->text('observation')->nullable();
            $table->uuid('constate_par_id')->nullable();
            $table->timestamp('constate_at')->nullable();
            $table->timestamps();

            $table->foreign('campagne_id')->references('id')->on('campagnes_inventaire')->onDelete('cascade');
            $table->index('campagne_id');
            $table->index('bien_id');
            $table->index('constat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_inventaire');
    }
};
