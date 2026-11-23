<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna_peran', function (Blueprint $table) {
            $table->foreignId('pengguna_id')->constrained('pengguna');
            $table->foreignId('peran_id')->constrained('peran');
            $table->primary(['pengguna_id', 'peran_id']);
            $table->index('peran_id', 'idx_pengguna_peran_peran');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna_peran');
    }
};
