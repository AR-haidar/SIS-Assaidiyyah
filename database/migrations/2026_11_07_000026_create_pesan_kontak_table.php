<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_kontak', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengirim', 150);
            $table->string('kontak', 100);
            $table->text('isi_pesan');
            $table->timestamp('dikirim_pada')->useCurrent();
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_kontak');
    }
};
