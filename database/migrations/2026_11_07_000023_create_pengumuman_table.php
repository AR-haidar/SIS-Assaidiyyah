<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table): void {
            $table->id();
            $table->string('judul', 200);
            $table->text('isi')->nullable();
            $table->string('nomor_surat', 100)->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('referensi');
            $table->enum('status', ['draft', 'terbit', 'arsip'])->default('draft');
            $table->boolean('disematkan')->default(false);
            $table->timestamp('tanggal_terbit')->nullable();
            $table->timestamp('tayang_selesai')->nullable();
            $table->foreignId('gambar_berkas_id')->nullable()->constrained('berkas');
            $table->foreignId('dibuat_oleh')->constrained('pengguna');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();

            $table->index(['status', 'tanggal_terbit']);
            $table->index('nomor_surat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
