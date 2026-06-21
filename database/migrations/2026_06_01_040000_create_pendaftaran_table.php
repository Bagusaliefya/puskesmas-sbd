<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->foreignId('id_pasien')->constrained('pasien', 'id_pasien')->cascadeOnDelete();
            $table->foreignId('id_petugas')->nullable()->constrained('petugas', 'id_petugas')->nullOnDelete();
            $table->date('tanggal_daftar');
            $table->text('keluhan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
