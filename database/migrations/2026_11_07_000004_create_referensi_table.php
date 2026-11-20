<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referensi', function (Blueprint $table) {
            $table->id();
            $table->string('kelompok', 40);
            $table->string('kode', 40);
            $table->string('label', 100);
            $table->smallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->unique(['kelompok', 'kode'], 'uq_referensi_kelompok_kode');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referensi');
    }
};
