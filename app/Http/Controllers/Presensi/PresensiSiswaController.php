<?php

namespace App\Http\Controllers\Presensi;

use App\Actions\Presensi\CatatPresensiSiswa;
use App\Enums\StatusPresensiSiswa;
use App\Http\Controllers\Controller;
use App\Http\Requests\Presensi\CatatPresensiSiswaRequest;
use App\Models\Pengguna;
use App\Models\PresensiSiswa;
use App\Models\Rombel;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresensiSiswaController extends Controller
{
    public function index(Request $request): View
    {
        $tanggalInput = trim((string) $request->query('tanggal', now()->format('Y-m-d')));
        $tanggal = $this->normalisasiTanggal($tanggalInput);
        $rombelId = $request->query('rombel_id');

        $daftarRombel = Rombel::query()
            ->with('tahunAjaran')
            ->orderByDesc('id')
            ->get();

        $rombelTerpilih = (is_numeric($rombelId) && (int) $rombelId > 0)
            ? $daftarRombel->firstWhere('id', (int) $rombelId)
            : $daftarRombel->first();

        $daftarSiswa = Siswa::query()
            ->with([
                'presensiSiswa' => fn ($q) => $q->whereDate('tanggal', $tanggal),
                'keanggotaanRombel.rombel',
            ])
            ->when($rombelTerpilih instanceof Rombel, function ($q) use ($rombelTerpilih): void {
                $q->whereHas('keanggotaanRombel', fn ($sub) => $sub->where('rombel_id', $rombelTerpilih->getKey()));
            })
            ->orderBy('nama_lengkap')
            ->get();

        $rekapHarian = PresensiSiswa::query()
            ->whereDate('tanggal', $tanggal)
            ->when($rombelTerpilih instanceof Rombel, function ($q) use ($rombelTerpilih): void {
                $q->whereHas('siswa.keanggotaanRombel', fn ($sub) => $sub->where('rombel_id', $rombelTerpilih->getKey()));
            })
            ->get();

        return view('presensi.siswa.index', [
            'tanggal' => $tanggal,
            'daftarRombel' => $daftarRombel,
            'rombelTerpilih' => $rombelTerpilih,
            'daftarSiswa' => $daftarSiswa,
            'rekapHarian' => $rekapHarian,
            'opsiStatus' => StatusPresensiSiswa::opsi(),
        ]);
    }

    public function store(
        CatatPresensiSiswaRequest $request,
        CatatPresensiSiswa $catatPresensiSiswa,
    ): RedirectResponse {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();
        $validated = $request->validated();

        $daftarItem = [];
        foreach ($validated['presensi'] as $item) {
            $daftarItem[] = [
                'siswa_id' => (int) $item['siswa_id'],
                'status' => (string) $item['status'],
                'keterangan' => $item['keterangan'] ?? null,
            ];
        }

        $catatPresensiSiswa->handle(
            tanggal: (string) $validated['tanggal'],
            daftarItem: $daftarItem,
            pencatat: $pengguna,
        );

        return back()->with('status', 'Presensi siswa berhasil disimpan.');
    }

    private function normalisasiTanggal(string $input): string
    {
        try {
            return Carbon::parse($input)->format('Y-m-d');
        } catch (\Throwable) {
            return now()->format('Y-m-d');
        }
    }
}
