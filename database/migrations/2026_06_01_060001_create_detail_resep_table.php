<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_resep', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_resep')->constrained('resep', 'id_resep')->cascadeOnDelete();
            $table->foreignId('id_obat')->constrained('obat', 'id_obat')->cascadeOnDelete();
            $table->integer('jumlah')->default(1);
            $table->string('dosis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_resep');
    }
};
