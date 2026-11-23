<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_kategori', function (Blueprint $table) {
            $table->foreignId('buku_id')->constrained('buku');
            $table->foreignId('kategori_buku_id')->constrained('kategori_buku');
            $table->primary(['buku_id', 'kategori_buku_id']);
            $table->index('kategori_buku_id', 'idx_buku_kategori_kategori');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diubah_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_kategori');
    }
};
