<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->id('id_dokter');
            $table->foreignId('id_pegawai')->constrained('pegawai', 'id_pegawai')->cascadeOnDelete();
            $table->string('spesialisasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
