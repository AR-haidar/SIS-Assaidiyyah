<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_buku', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_buku');
    }
};
