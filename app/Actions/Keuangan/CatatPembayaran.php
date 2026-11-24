<?php

namespace App\Actions\Keuangan;

use App\Enums\StatusVerifikasi;
use App\Enums\SumberPembayaran;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Tagihan;
use App\Services\BerkasService;
use App\Support\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CatatPembayaran
{
    public function __construct(
        private readonly BerkasService $berkasService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(
        Tagihan $tagihan,
        array $data,
        SumberPembayaran $sumber,
        ?Pengguna $pelaku = null,
        ?UploadedFile $buktiBerkas = null,
        bool $langsungSetujui = false,
    ): Pembayaran {
        $nominalBayar = (float) ($data['nominal'] ?? 0);
        if ($nominalBayar <= 0) {
            throw ValidationException::withMessages([
                'nominal' => ['Nominal pembayaran harus lebih besar dari nol.'],
            ]);
        }

        return DB::transaction(function () use (
            $tagihan,
            $data,
            $nominalBayar,
            $sumber,
            $pelaku,
            $buktiBerkas,
            $langsungSetujui,
        ): Pembayaran {
            /** @var Tagihan $targetTagihan */
            $targetTagihan = Tagihan::query()
                ->lockForUpdate()
                ->findOrFail($tagihan->getKey());

            $pembayaranAktif = Pembayaran::query()
                ->where('tagihan_id', $targetTagihan->getKey())
                ->latest('id')
                ->first();

            if ($pembayaranAktif instanceof Pembayaran) {
                if ($pembayaranAktif->status_verifikasi === StatusVerifikasi::Disetujui) {
                    throw ValidationException::withMessages([
                        'nominal' => ['Pembayaran untuk tagihan ini sudah disetujui.'],
                    ]);
                }

                if ($pembayaranAktif->status_verifikasi === StatusVerifikasi::Menunggu) {
                    throw ValidationException::withMessages([
                        'nominal' => ['Sudah terdapat pengajuan pembayaran yang menunggu verifikasi untuk tagihan ini.'],
                    ]);
                }

                if ($pembayaranAktif->status_verifikasi === StatusVerifikasi::Ditolak) {
                    $pembayaranAktif->delete();
                }
            }

            if ($nominalBayar > ((float) $targetTagihan->nominal + 0.001)) {
                throw ValidationException::withMessages([
                    'nominal' => ['Nominal pembayaran melebihi nominal tagihan.'],
                ]);
            }

            $buktiBerkasId = null;
            if ($buktiBerkas instanceof UploadedFile) {
                $berkas = $this->berkasService->simpan(
                    $buktiBerkas,
                    'keuangan/bukti/'.$targetTagihan->getKey(),
                    BerkasService::DISK_PRIVATE,
                );
                $buktiBerkasId = $berkas->getKey();
            }

            $disetujuiLangsung = $sumber === SumberPembayaran::InputPetugas && $langsungSetujui;

            $tanggalPembayaran = isset($data['tanggal_pembayaran']) && trim((string) $data['tanggal_pembayaran']) !== ''
                ? Carbon::parse((string) $data['tanggal_pembayaran'])->format('Y-m-d')
                : ($disetujuiLangsung ? now()->format('Y-m-d') : null);

            $pembayaran = Pembayaran::query()->create([
                'tagihan_id' => $targetTagihan->getKey(),
                'nominal' => round($nominalBayar, 2),
                'metode_pembayaran_id' => (int) $data['metode_pembayaran_id'],
                'tanggal_pengajuan' => now(),
                'tanggal_pembayaran' => $tanggalPembayaran,
                'sumber' => $sumber,
                'bukti_berkas_id' => $buktiBerkasId,
                'status_verifikasi' => $disetujuiLangsung
                    ? StatusVerifikasi::Disetujui
                    : StatusVerifikasi::Menunggu,
                'diverifikasi_oleh' => $disetujuiLangsung ? $pelaku?->getKey() : null,
                'diverifikasi_pada' => $disetujuiLangsung ? now() : null,
            ]);

            LogAktivitas::catat(
                modul: 'keuangan',
                aksi: 'catat_pembayaran',
                objek: $pembayaran,
                ringkasan: 'Mencatat pembayaran Rp'.number_format($nominalBayar, 0, ',', '.')
                    .' untuk tagihan #'.$targetTagihan->getKey().' ('.$sumber->value.')',
            );

            return $pembayaran;
        });
    }
}
