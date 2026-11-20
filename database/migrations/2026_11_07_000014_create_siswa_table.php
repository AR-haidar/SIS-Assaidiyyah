<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->nullable()->unique()->constrained('pengguna');
            $table->string('nis', 20)->nullable()->unique();
            $table->string('nisn', 10)->nullable()->unique();
            $table->char('nik', 16)->nullable()->unique();
            $table->string('nama_lengkap', 150)->nullable();
            $table->string('tempat_lahir', 80)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->smallInteger('tahun_masuk')->nullable();
            $table->foreignId('status_masuk_id')->nullable()->constrained('referensi');
            $table->foreignId('status_kesiswaan_id')->nullable()->constrained('referensi');
            $table->enum('status_data', ['draft', 'terverifikasi'])->default('draft');
            $table->string('nama_ayah', 150)->nullable();
            $table->char('nik_ayah', 16)->nullable();
            $table->foreignId('pekerjaan_ayah_id')->nullable()->constrained('referensi');
            $table->string('nama_ibu', 150)->nullable();
            $table->char('nik_ibu', 16)->nullable();
            $table->string('no_whatsapp_orang_tua', 20)->nullable();
            $table->index('nama_lengkap', 'idx_siswa_nama');
            $table->index('status_data', 'idx_siswa_status_data');
            $table->index('no_whatsapp_orang_tua', 'idx_siswa_whatsapp_orang_tua');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
