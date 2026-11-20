<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman_sasaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengumuman_id')->constrained('pengumuman');
            $table->enum('jenis_sasaran', ['publik', 'peran', 'rombel']);
            $table->foreignId('peran_id')->nullable()->constrained('peran');
            $table->foreignId('rombel_id')->nullable()->constrained('rombel');
            $table->index('pengumuman_id', 'idx_pengumuman_sasaran_pengumuman');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
        });

        DB::statement("ALTER TABLE pengumuman_sasaran ADD CONSTRAINT ck_pengumuman_sasaran_konsisten CHECK ((jenis_sasaran = 'publik' AND peran_id IS NULL AND rombel_id IS NULL) OR (jenis_sasaran = 'peran' AND peran_id IS NOT NULL AND rombel_id IS NULL) OR (jenis_sasaran = 'rombel' AND rombel_id IS NOT NULL AND peran_id IS NULL))");
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman_sasaran');
    }
};
