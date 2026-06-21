<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokter', function (Blueprint $table) {
            $table->enum('status', ['tersedia', 'sibuk'])->default('tersedia')->after('spesialisasi');
        });

        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->foreignId('id_dokter')->nullable()->after('id_petugas')
                ->constrained('dokter', 'id_dokter')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['id_dokter']);
            $table->dropColumn('id_dokter');
        });

        Schema::table('dokter', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
