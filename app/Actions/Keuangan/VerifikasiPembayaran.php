<?php

namespace App\Actions\Keuangan;

use App\Enums\StatusVerifikasi;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Support\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VerifikasiPembayaran
{
    public function handle(
        Pembayaran $pembayaran,
        StatusVerifikasi $statusBaru,
        Pengguna $verifikator,
        ?string $tanggalPembayaran = null,
        ?string $alasanPenolakan = null,
    ): Pembayaran {
        if ($statusBaru === StatusVerifikasi::Menunggu) {
            throw ValidationException::withMessages([
                'status_verifikasi' => ['Pilih status disetujui atau ditolak saat memverifikasi pembayaran.'],
            ]);
        }

        if ($statusBaru === StatusVerifikasi::Ditolak && ($alasanPenolakan === null || trim($alasanPenolakan) === '')) {
            throw ValidationException::withMessages([
                'alasan_penolakan' => ['Alasan penolakan wajib diisi jika pembayaran ditolak.'],
            ]);
        }

        return DB::transaction(function () use (
            $pembayaran,
            $statusBaru,
            $verifikator,
            $tanggalPembayaran,
            $alasanPenolakan,
        ): Pembayaran {
            /** @var Pembayaran $target */
            $target = Pembayaran::query()
                ->lockForUpdate()
                ->findOrFail($pembayaran->getKey());

            $target->status_verifikasi = $statusBaru;
            $target->diverifikasi_oleh = $verifikator->getKey();
            $target->diverifikasi_pada = now();

            if ($statusBaru === StatusVerifikasi::Disetujui) {
                $target->tanggal_pembayaran = $tanggalPembayaran !== null && trim($tanggalPembayaran) !== ''
                    ? Carbon::parse($tanggalPembayaran)->format('Y-m-d')
                    : ($target->tanggal_pembayaran?->format('Y-m-d') ?? now()->format('Y-m-d'));
                $target->alasan_penolakan = null;
            } else {
                $target->alasan_penolakan = trim((string) $alasanPenolakan);
            }

            $target->save();

            LogAktivitas::catat(
                modul: 'keuangan',
                aksi: 'verifikasi_pembayaran',
                objek: $target,
                ringkasan: 'Verifikasi pembayaran #'.$target->getKey().' menjadi '.$statusBaru->value,
            );

            return $target;
        });
    }
}
