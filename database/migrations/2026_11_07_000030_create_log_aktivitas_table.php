<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna');
            $table->string('modul', 40);
            $table->string('aksi', 40);
            $table->string('objek', 60)->nullable();
            $table->bigInteger('objek_id')->nullable();
            $table->text('ringkasan')->nullable();
            $table->timestamp('waktu')->useCurrent();
            $table->index(['modul', 'waktu'], 'idx_log_modul_waktu');
            $table->index(['pengguna_id', 'waktu'], 'idx_log_pengguna_waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
