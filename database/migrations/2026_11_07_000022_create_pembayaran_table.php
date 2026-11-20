<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan');
            $table->decimal('nominal', 14, 2);
            $table->foreignId('metode_pembayaran_id')->constrained('referensi');
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->date('tanggal_pembayaran')->nullable();
            $table->enum('sumber', ['pengajuan_wali', 'input_petugas']);
            $table->foreignId('bukti_berkas_id')->nullable()->constrained('berkas');
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('pengguna');
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('diubah_pada')->useCurrent();
            $table->index('tagihan_id', 'idx_pembayaran_tagihan');
            $table->index(['status_verifikasi', 'tanggal_pengajuan'], 'idx_pembayaran_status');
            $table->timestamp('dihapus_pada')->nullable();
        });

        DB::statement("CREATE UNIQUE INDEX uq_pembayaran_aktif_per_tagihan ON pembayaran (tagihan_id) WHERE status_verifikasi IN ('menunggu', 'disetujui') AND dihapus_pada IS NULL");
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
