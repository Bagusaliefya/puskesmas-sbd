<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id('id_resep');
            $table->foreignId('id_pemeriksaan')->constrained('pemeriksaan', 'id_pemeriksaan')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};
