<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_siswa', function (Blueprint $table) {
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->enum('jenis', ['pasfoto', 'kartu_keluarga', 'akta_lahir']);
            $table->foreignId('berkas_id')->constrained('berkas');
            $table->primary(['siswa_id', 'jenis']);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_siswa');
    }
};
