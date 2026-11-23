<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('password_hash');
            $table->enum('jenis_akun', ['staf', 'siswa']);
            $table->string('nama_lengkap', 150)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('terakhir_login')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
