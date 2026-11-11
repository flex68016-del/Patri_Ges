<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journaux_audit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('acteur_id');
            $table->string('action', 255);
            $table->string('objet_type', 100)->nullable();
            $table->uuid('objet_id')->nullable();
            $table->timestamp('date')->useCurrent();
            $table->json('detail')->nullable();

            $table->index('acteur_id');
            $table->index('action');
            $table->index('date');
            $table->index(['objet_type', 'objet_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journaux_audit');
    }
};
