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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 50)->unique();
            $table->string('nom', 255);
            $table->enum('type', ['ministere', 'direction', 'service']);
            $table->uuid('parent_id')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('services')->onDelete('set null');
            $table->index('code');
            $table->index('parent_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
