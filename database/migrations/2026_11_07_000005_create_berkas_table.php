<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asli', 255);
            $table->string('path', 500);
            $table->string('mime', 100);
            $table->bigInteger('ukuran_byte');
            $table->foreignId('diunggah_oleh')->constrained('pengguna');
            $table->timestamp('diunggah_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas');
    }
};
