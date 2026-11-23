<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('penulis', 150);
            $table->string('penerbit', 150)->nullable();
            $table->smallInteger('tahun_terbit')->nullable();
            $table->text('sinopsis')->nullable();
            $table->foreignId('sampul_berkas_id')->nullable()->constrained('berkas');
            $table->foreignId('naskah_berkas_id')->nullable()->constrained('berkas');
            $table->enum('status_publikasi', ['draft', 'aktif'])->default('draft');
            $table->foreignId('dibuat_oleh')->constrained('pengguna');
            $table->index('judul', 'idx_buku_judul');
            $table->index('status_publikasi', 'idx_buku_status');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
