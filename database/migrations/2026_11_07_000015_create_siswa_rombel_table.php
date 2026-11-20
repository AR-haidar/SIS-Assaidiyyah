<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('rombel_id')->constrained('rombel');
            $table->unique(['siswa_id', 'rombel_id'], 'uq_siswa_rombel');
            $table->index('rombel_id', 'idx_siswa_rombel_rombel');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_rombel');
    }
};
