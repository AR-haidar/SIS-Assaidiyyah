<?php

namespace App\Services\Keuangan;

use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Builder;

class RekapTagihanQuery
{
    public const OPSI_STATUS = [
        'belum_dibayar'       => 'Belum Dibayar',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'lunas'               => 'Lunas',
        'jatuh_tempo'         => 'Jatuh Tempo',
    ];

    public function dasar(?int $tahunAjaranId): Builder
    {
        return Tagihan::query()->when(
            $tahunAjaranId,
            fn (Builder $q) => $q->where('tagihan.tahun_ajaran_id', $tahunAjaranId),
            fn (Builder $q) => $q->whereRaw('1 = 0'), // tanpa tahun ajaran aktif: kosong
        );
    }

    /**
     * @param array{q?:?string, jenis?:?int, rombel?:?int, status?:?string} $filter
     */
    public function daftar(?int $tahunAjaranId, array $filter): Builder
    {
        $q = $this->dasar($tahunAjaranId);

        if (! empty($filter['q'])) {
            $like = '%' . addcslashes($filter['q'], '%_\\') . '%';

            $q->whereHas('siswa', fn (Builder $s) => $s->where(
                fn (Builder $w) => $w->where('nama_lengkap', 'ilike', $like)
                    ->orWhere('nisn', 'like', $like)
            ));
        }

        if (! empty($filter['jenis'])) {
            $q->where('jenis_tagihan_id', $filter['jenis']);
        }

        if (! empty($filter['rombel'])) {
            $q->whereHas('siswa.rombel', fn (Builder $r) => $r->where('rombel.id', $filter['rombel']));
        }

        match ($filter['status'] ?? null) {
            'lunas' => $q->whereHas('pembayaran', fn (Builder $p) => $p->disetujui()),
            'menunggu_verifikasi' => $q->whereHas('pembayaran', fn (Builder $p) => $p->menunggu()),
            'belum_dibayar' => $q->whereDoesntHave('pembayaran', fn (Builder $p) => $p->whereIn(
                'status_verifikasi', ['menunggu', 'disetujui']
            )),
            'jatuh_tempo' => $q->whereDoesntHave('pembayaran', fn (Builder $p) => $p->whereIn(
                'status_verifikasi', ['menunggu', 'disetujui']
            ))->whereDate('jatuh_tempo', '<', today()),
            default => null,
        };

        return $q;
    }

    /**
     * @return array{semua:int, menunggu:int, lunas:int}
     */
    public function ringkasan(?int $tahunAjaranId): array
    {
        return [
            'semua'    => $this->dasar($tahunAjaranId)->count(),
            'menunggu' => $this->dasar($tahunAjaranId)
                ->whereHas('pembayaran', fn (Builder $p) => $p->menunggu())->count(),
            'lunas'    => $this->dasar($tahunAjaranId)
                ->whereHas('pembayaran', fn (Builder $p) => $p->disetujui())->count(),
        ];
    }

    /**
     * Label status untuk tampilan. Butuh relasi pembayaran sudah di-load.
     */
    public static function labelStatus(Tagihan $tagihan): string
    {
        return match ($tagihan->status_pembayaran) {
            'lunas'               => 'Lunas',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            default               => $tagihan->jatuh_tempo->lt(today()) ? 'Jatuh Tempo' : 'Belum Dibayar',
        };
    }
}