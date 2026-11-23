<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_guru_publik', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai');
            $table->string('nama_tampil', 150);
            $table->string('jabatan_tampil', 150)->nullable();
            $table->text('bio_singkat')->nullable();
            $table->foreignId('foto_berkas_id')->nullable()->constrained('berkas');
            $table->smallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();

            $table->index(['aktif', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_guru_publik');
    }
};
