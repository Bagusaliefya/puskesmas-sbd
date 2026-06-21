<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->dropForeign(['id_dokter']);
            $table->foreign('id_dokter')->references('id_dokter')->on('dokter')->restrictOnDelete();
        });

        Schema::table('detail_resep', function (Blueprint $table) {
            $table->dropForeign(['id_obat']);
            $table->foreign('id_obat')->references('id_obat')->on('obat')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->dropForeign(['id_dokter']);
            $table->foreign('id_dokter')->references('id_dokter')->on('dokter')->cascadeOnDelete();
        });

        Schema::table('detail_resep', function (Blueprint $table) {
            $table->dropForeign(['id_obat']);
            $table->foreign('id_obat')->references('id_obat')->on('obat')->cascadeOnDelete();
        });
    }
};
