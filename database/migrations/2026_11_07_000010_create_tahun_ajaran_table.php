<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 9)->unique();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('aktif')->default(false);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });

        DB::statement("CREATE UNIQUE INDEX uq_tahun_ajaran_aktif ON tahun_ajaran (aktif) WHERE aktif = true AND dihapus_pada IS NULL");
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
