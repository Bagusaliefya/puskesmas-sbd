<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nama_pegawai');
            $table->string('jabatan');
            $table->string('no_hp', 20)->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
