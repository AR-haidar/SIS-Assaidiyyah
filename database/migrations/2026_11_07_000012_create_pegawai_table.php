<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->nullable()->unique()->constrained('pengguna');
            $table->string('nip', 30)->nullable()->unique();
            $table->char('nuptk', 16)->nullable()->unique();
            $table->char('nik', 16)->nullable()->unique();
            $table->string('nama_lengkap', 150)->nullable();
            $table->string('tempat_lahir', 80)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->foreignId('pendidikan_terakhir_id')->nullable()->constrained('referensi');
            $table->string('program_studi', 100)->nullable();
            $table->string('no_hp_wa', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->foreignId('status_kepegawaian_id')->nullable()->constrained('referensi');
            $table->foreignId('jenis_gtk_id')->nullable()->constrained('referensi');
            $table->string('mata_pelajaran_diampu', 255)->nullable();
            $table->string('nomor_sk_pengangkatan', 100)->nullable();
            $table->foreignId('pasfoto_berkas_id')->nullable()->constrained('berkas');
            $table->index('nama_lengkap', 'idx_pegawai_nama');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
