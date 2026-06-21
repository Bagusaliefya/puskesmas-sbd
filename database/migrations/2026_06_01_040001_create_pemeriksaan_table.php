<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id('id_pemeriksaan');
            $table->foreignId('id_pendaftaran')->constrained('pendaftaran', 'id_pendaftaran')->cascadeOnDelete();
            $table->foreignId('id_dokter')->constrained('dokter', 'id_dokter')->cascadeOnDelete();
            $table->text('diagnosa')->nullable();
            $table->date('tanggal_periksa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
