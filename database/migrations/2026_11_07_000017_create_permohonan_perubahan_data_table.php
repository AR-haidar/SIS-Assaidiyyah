<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_perubahan_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('diajukan_oleh')->constrained('pengguna');
            $table->text('keterangan');
            $table->enum('status', ['diajukan', 'diproses', 'selesai', 'ditolak'])->default('diajukan');
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->foreignId('diproses_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('diproses_pada')->nullable();
            $table->text('catatan_proses')->nullable();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->index(['status', 'diajukan_pada'], 'idx_permohonan_status');
            $table->index('siswa_id', 'idx_permohonan_siswa');
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_perubahan_data');
    }
};
