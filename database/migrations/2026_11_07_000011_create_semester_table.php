<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran');
            $table->enum('nama', ['ganjil', 'genap']);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('aktif')->default(false);
            $table->unique(['tahun_ajaran_id', 'nama'], 'uq_semester_tahun_nama');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });

        DB::statement('CREATE UNIQUE INDEX uq_semester_aktif ON semester (aktif) WHERE aktif = true AND dihapus_pada IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('semester');
    }
};
