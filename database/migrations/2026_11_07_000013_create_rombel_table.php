<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran');
            $table->smallInteger('tingkat');
            $table->string('nama', 20);
            $table->foreignId('wali_kelas_id')->nullable()->constrained('pegawai');
            $table->unique(['tahun_ajaran_id', 'nama'], 'uq_rombel_tahun_nama');
            $table->index('wali_kelas_id', 'idx_rombel_wali_kelas');
            $table->index('tingkat', 'idx_rombel_tingkat');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });

        DB::statement('ALTER TABLE rombel ADD CONSTRAINT ck_rombel_tingkat CHECK (tingkat BETWEEN 1 AND 6)');
    }

    public function down(): void
    {
        Schema::dropIfExists('rombel');
    }
};
