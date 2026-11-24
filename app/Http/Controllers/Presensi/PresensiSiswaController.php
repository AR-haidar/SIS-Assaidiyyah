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
                'presensi' => fn ($q) => $q->whereDate('tanggal', $tanggal),
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

    public function rekapWaliKelas(Request $request): View
    {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();
        $bulan = trim((string) $request->query('bulan', ''));
        $tahun = trim((string) $request->query('tahun', ''));

        $rombel = Rombel::query()
            ->with(['tahunAjaran', 'waliKelas'])
            ->whereHas('waliKelas', fn ($q) => $q->where('pengguna_id', $pengguna->getKey()))
            ->orderByDesc('id')
            ->first();

        $daftarSiswa = Siswa::query()
            ->with(['presensi' => function ($q) use ($bulan, $tahun): void {
                if ($bulan !== '' && is_numeric($bulan)) {
                    $q->whereMonth('tanggal', (int) $bulan);
                }
                if ($tahun !== '' && is_numeric($tahun)) {
                    $q->whereYear('tanggal', (int) $tahun);
                }
            }])
            ->whereHas('keanggotaanRombel', fn ($q) => $q->where('rombel_id', $rombel?->getKey() ?? 0))
            ->orderBy('nama_lengkap')
            ->get();

        $rekapSiswa = $daftarSiswa->map(function (Siswa $siswa): array {
            $logs = $siswa->presensi;
            $hadir = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'hadir')->count();
            $izin = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'izin')->count();
            $sakit = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'sakit')->count();
            $alpa = $logs->filter(fn ($p) => ($p->status?->value ?? (string) $p->status) === 'alpa')->count();
            $totalHari = $logs->count();
            $persentase = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 1) : 0.0;

            return [
                'nama_lengkap' => $siswa->nama_lengkap,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persentase' => $persentase,
            ];
        });

        return view('wali-kelas.presensi-absensi', [
            'rombelTerpilih' => $rombel,
            'rekapSiswa' => $rekapSiswa,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }

    public function riwayatSiswa(Request $request): View
    {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();
        $bulan = trim((string) $request->query('bulan', 'all'));

        $siswa = Siswa::query()
            ->with(['keanggotaanRombel.rombel.waliKelas'])
            ->where('pengguna_id', $pengguna->getKey())
            ->first();

        $riwayatPresensi = collect();
        if ($siswa instanceof Siswa) {
            $riwayatPresensi = PresensiSiswa::query()
                ->where('siswa_id', $siswa->getKey())
                ->when($bulan !== '' && $bulan !== 'all' && is_numeric($bulan), fn ($q) => $q->whereMonth('tanggal', (int) $bulan))
                ->orderByDesc('tanggal')
                ->get();
        }

        return view('siswa.absensi', [
            'siswa' => $siswa,
            'riwayatPresensi' => $riwayatPresensi,
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
