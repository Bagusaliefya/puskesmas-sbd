<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->enum('tipe_pendaftaran', ['petugas', 'mandiri'])->default('petugas')->after('id_petugas');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('tipe_pendaftaran');
        });
    }
};
