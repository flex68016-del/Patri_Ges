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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('libelle', 255)->unique();
            $table->enum('type_bien', ['immobilier', 'vehicule', 'equipement']);
            $table->integer('duree_amortissement');
            $table->boolean('amortissable');
            $table->timestamps();

            $table->index('type_bien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
