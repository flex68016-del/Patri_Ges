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
        Schema::create('biens_immobiliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bien_id')->unique();
            $table->text('adresse')->nullable();
            $table->decimal('superficie', 10, 2)->nullable();
            $table->string('titre_foncier', 255)->nullable();
            $table->string('coordonnees_gps', 100)->nullable();
            $table->timestamps();

            $table->foreign('bien_id')->references('id')->on('biens')->onDelete('cascade');
            $table->index('bien_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biens_immobiliers');
    }
};
