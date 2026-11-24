<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi_sekolah', function (Blueprint $table): void {
            $table->id();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->string('tingkat', 100)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->smallInteger('tahun')->nullable();
            $table->date('tanggal')->nullable();
            $table->foreignId('foto_berkas_id')->nullable()->constrained('berkas');
            $table->smallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();

            $table->index(['aktif', 'urutan']);
            $table->index('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_sekolah');
    }
};
