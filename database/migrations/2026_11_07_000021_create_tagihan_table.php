<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('jenis_tagihan_id')->constrained('jenis_tagihan');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran');
            $table->date('periode_bulan')->nullable();
            $table->decimal('nominal', 14, 2);
            $table->date('jatuh_tempo');
            $table->unique(['siswa_id', 'jenis_tagihan_id', 'tahun_ajaran_id', 'periode_bulan'], 'uq_tagihan_siswa_jenis_periode');
            $table->index(['tahun_ajaran_id', 'jenis_tagihan_id'], 'idx_tagihan_tahun_jenis');
            $table->index('jatuh_tempo', 'idx_tagihan_jatuh_tempo');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
