<?php

namespace App\Services\Keuangan;

use App\Enums\StatusVerifikasi;
use App\Enums\SumberPembayaran;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Tagihan;
use App\Services\BerkasService; // sesuaikan setelah Lead push
use App\Support\Aktivitas;      // sesuaikan setelah Lead push
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembayaranService
{
    public function __construct(private BerkasService $berkas) {}

    /**
     * Siswa/orang tua mengajukan bukti bayar -> status menunggu.
     */
    public function ajukan(Tagihan $tagihan, array $data, UploadedFile $bukti, Pengguna $pengguna): Pembayaran
    {
        try {
            return DB::transaction(function () use ($tagihan, $data, $bukti) {
                $tagihan = Tagihan::lockForUpdate()->findOrFail($tagihan->id);

                $this->pastikanBelumAdaPembayaranBerlaku($tagihan);
                $this->pastikanNominalSama($tagihan, $data['nominal']);

                $berkas = $this->berkas->simpan($bukti, 'bukti-pembayaran');

                $pembayaran = $tagihan->pembayaran()->create([
                    'nominal'              => $tagihan->nominal,
                    'metode_pembayaran_id' => $data['metode_pembayaran_id'],
                    'tanggal_pembayaran'   => $data['tanggal_pembayaran'] ?? null,
                    'sumber'               => SumberPembayaran::PengajuanWali,
                    'bukti_berkas_id'      => $berkas->id,
                    'status_verifikasi'    => StatusVerifikasi::Menunggu,
                ]);

                Aktivitas::catat('keuangan', 'ajukan_pembayaran', $pembayaran,
                    "Pengajuan bukti bayar tagihan #{$tagihan->id}");

                return $pembayaran;
            });
        } catch (QueryException $e) {
            $this->tanganiPelanggaranUnik($e);
        }
    }

    /**
     * Petugas keuangan menginput pembayaran manual -> langsung disetujui.
     */
    public function inputPetugas(Tagihan $tagihan, array $data, Pengguna $petugas, ?UploadedFile $bukti = null): Pembayaran
    {
        try {
            return DB::transaction(function () use ($tagihan, $data, $petugas, $bukti) {
                $tagihan = Tagihan::lockForUpdate()->findOrFail($tagihan->id);

                $this->pastikanBelumAdaPembayaranBerlaku($tagihan);
                $this->pastikanNominalSama($tagihan, $data['nominal']);

                $berkas = $bukti ? $this->berkas->simpan($bukti, 'bukti-pembayaran') : null;

                $pembayaran = $tagihan->pembayaran()->create([
                    'nominal'              => $tagihan->nominal,
                    'metode_pembayaran_id' => $data['metode_pembayaran_id'],
                    'tanggal_pembayaran'   => $data['tanggal_pembayaran'] ?? null,
                    'sumber'               => SumberPembayaran::InputPetugas,
                    'bukti_berkas_id'      => $berkas?->id,
                    'status_verifikasi'    => StatusVerifikasi::Disetujui,
                    'diverifikasi_oleh'    => $petugas->id,
                    'diverifikasi_pada'    => now(),
                ]);

                Aktivitas::catat('keuangan', 'input_pembayaran', $pembayaran,
                    "Input manual pembayaran tagihan #{$tagihan->id}");

                return $pembayaran;
            });
        } catch (QueryException $e) {
            $this->tanganiPelanggaranUnik($e);
        }
    }

    public function setujui(Pembayaran $pembayaran, Pengguna $petugas): Pembayaran
    {
        return $this->putuskan($pembayaran, $petugas, StatusVerifikasi::Disetujui, null);
    }

    public function tolak(Pembayaran $pembayaran, Pengguna $petugas, string $alasan): Pembayaran
    {
        if (trim($alasan) === '') {
            throw ValidationException::withMessages([
                'alasan_penolakan' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        return $this->putuskan($pembayaran, $petugas, StatusVerifikasi::Ditolak, $alasan);
    }

    private function putuskan(Pembayaran $pembayaran, Pengguna $petugas, StatusVerifikasi $status, ?string $alasan): Pembayaran
    {
        return DB::transaction(function () use ($pembayaran, $petugas, $status, $alasan) {
            // Kunci baris tagihan dulu (urutan kunci konsisten), lalu pembayarannya.
            Tagihan::lockForUpdate()->findOrFail($pembayaran->tagihan_id);
            $pembayaran = Pembayaran::lockForUpdate()->findOrFail($pembayaran->id);

            if ($pembayaran->status_verifikasi !== StatusVerifikasi::Menunggu) {
                throw ValidationException::withMessages([
                    'pembayaran' => 'Pembayaran ini sudah diproses dan tidak bisa diubah lagi.',
                ]);
            }

            $pembayaran->update([
                'status_verifikasi' => $status,
                'diverifikasi_oleh' => $petugas->id,
                'diverifikasi_pada' => now(),
                'alasan_penolakan'  => $alasan,
            ]);

            Aktivitas::catat(
                'keuangan',
                $status === StatusVerifikasi::Disetujui ? 'setujui_pembayaran' : 'tolak_pembayaran',
                $pembayaran,
                $alasan,
            );

            return $pembayaran;
        });
    }

    private function pastikanBelumAdaPembayaranBerlaku(Tagihan $tagihan): void
    {
        $ada = $tagihan->pembayaran()
            ->whereIn('status_verifikasi', [StatusVerifikasi::Menunggu, StatusVerifikasi::Disetujui])
            ->exists();

        if ($ada) {
            throw ValidationException::withMessages([
                'tagihan' => 'Tagihan ini sudah memiliki pembayaran yang menunggu verifikasi atau sudah lunas.',
            ]);
        }
    }

    private function pastikanNominalSama(Tagihan $tagihan, string|int|float $nominal): void
    {
        if (bccomp((string) $tagihan->nominal, number_format((float) $nominal, 2, '.', ''), 2) !== 0) {
            throw ValidationException::withMessages([
                'nominal' => 'Nominal pembayaran harus sama dengan nominal tagihan (tidak ada cicilan).',
            ]);
        }
    }

    private function tanganiPelanggaranUnik(QueryException $e): never
    {
        if (($e->errorInfo[0] ?? null) === '23505') {
            throw ValidationException::withMessages([
                'tagihan' => 'Tagihan ini sudah memiliki pembayaran yang berlaku.',
            ]);
        }

        throw $e;
    }
}