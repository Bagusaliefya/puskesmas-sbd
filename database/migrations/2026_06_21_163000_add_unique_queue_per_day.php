<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('UPDATE pendaftaran SET no_antrian = id_pendaftaran WHERE no_antrian IS NULL');

        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->unsignedInteger('no_antrian')->nullable(false)->default(0)->change();
            $table->unique(['no_antrian', 'tanggal_daftar']);
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropUnique(['no_antrian', 'tanggal_daftar']);
            $table->unsignedInteger('no_antrian')->nullable()->default(null)->change();
        });
    }
};
