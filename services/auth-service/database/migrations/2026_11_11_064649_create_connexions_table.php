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
        Schema::create('connexions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('utilisateur_id');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('connecte_at');
            $table->timestamp('deconnecte_at')->nullable();
            $table->timestamps();

            $table->foreign('utilisateur_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('utilisateur_id');
            $table->index('connecte_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connexions');
    }
};
