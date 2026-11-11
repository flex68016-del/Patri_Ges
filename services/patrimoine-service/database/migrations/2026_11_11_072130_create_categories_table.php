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
            $table->string('code', 50)->unique();
            $table->string('nom', 255);
            $table->enum('type_bien', ['immobilier', 'vehicule', 'equipement']);
            $table->text('description')->nullable();
            $table->integer('duree_vie');
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->index('type_bien');
            $table->index('actif');
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
