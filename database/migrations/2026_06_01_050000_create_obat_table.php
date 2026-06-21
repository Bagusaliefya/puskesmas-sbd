<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat', function (Blueprint $table) {
            $table->id('id_obat');
            $table->string('nama_obat');
            $table->integer('stok')->default(0);
            $table->decimal('harga', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
