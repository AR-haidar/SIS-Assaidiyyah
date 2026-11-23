<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->text('deskripsi_singkat')->nullable();
            $table->text('visi')->nullable();
            $table->string('jenjang', 20)->nullable();
            $table->string('status_akreditasi', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->foreignId('foto_gedung_berkas_id')->nullable()->constrained('berkas');
            $table->decimal('target_kehadiran_pegawai', 5, 2)->default(95.00);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
