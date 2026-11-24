<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table): void {
            $table->string('judul', 250)->change();
        });
    }

    public function down(): void
    {
        if (DB::table('buku')->whereRaw('length(judul) > 200')->exists()) {
            throw new LogicException('Masih ada judul buku yang melebihi 200 karakter.');
        }

        Schema::table('buku', function (Blueprint $table): void {
            $table->string('judul', 200)->change();
        });
    }
};
