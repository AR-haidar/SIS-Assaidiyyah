<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_pegawai', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin_tugas_luar', 'sakit']);
            $table->text('keterangan')->nullable();
            $table->enum('status_validasi', ['menunggu', 'valid', 'ditolak'])->default('menunggu');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('divalidasi_pada')->nullable();
            $table->enum('sumber', ['biometrik', 'manual']);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();

            $table->unique(['pegawai_id', 'tanggal']);
            $table->index(['tanggal', 'status_validasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_pegawai');
    }
};
