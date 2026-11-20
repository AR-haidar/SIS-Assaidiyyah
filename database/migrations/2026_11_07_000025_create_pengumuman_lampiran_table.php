<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman_lampiran', function (Blueprint $table) {
            $table->foreignId('pengumuman_id')->constrained('pengumuman');
            $table->foreignId('berkas_id')->constrained('berkas');
            $table->primary(['pengumuman_id', 'berkas_id']);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman_lampiran');
    }
};
