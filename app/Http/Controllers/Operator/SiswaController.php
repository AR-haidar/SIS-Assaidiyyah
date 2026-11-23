<?php

namespace App\Http\Controllers\Operator;

use App\Actions\Siswa\AturRombelSiswa;
use App\Actions\Siswa\KelolaAkunSiswa;
use App\Actions\Siswa\SimpanSiswa;
use App\Actions\Siswa\VerifikasiSiswa;
use App\Enums\JenisKelamin;
use App\Enums\StatusDataSiswa;
use App\Http\Controllers\Controller;
use App\Http\Requests\Siswa\SimpanSiswaRequest;
use App\Http\Requests\Siswa\VerifikasiSiswaRequest;
use App\Models\Referensi;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $cari = trim((string) $request->query('q', ''));
        $statusData = trim((string) $request->query('status_data', ''));
        $rombelId = $request->query('rombel_id');

        $query = Siswa::query()
            ->with([
                'statusMasuk',
                'statusKesiswaan',
                'pengguna',
                'keanggotaanRombel.rombel.tahunAjaran',
            ])
            ->when($cari !== '', function ($q) use ($cari): void {
                $q->where(function ($sub) use ($cari): void {
                    $sub->where('nama_lengkap', 'like', '%'.$cari.'%')
                        ->orWhere('nisn', 'like', '%'.$cari.'%')
                        ->orWhere('nis', 'like', '%'.$cari.'%')
                        ->orWhere('nik', 'like', '%'.$cari.'%');
                });
            })
            ->when($statusData !== '', function ($q) use ($statusData): void {
                $q->where('status_data', $statusData);
            })
            ->when(is_numeric($rombelId) && (int) $rombelId > 0, function ($q) use ($rombelId): void {
                $q->whereHas('keanggotaanRombel', function ($sub) use ($rombelId): void {
                    $sub->where('rombel_id', (int) $rombelId);
                });
            })
            ->orderBy('nama_lengkap')
            ->orderByDesc('id');

        $daftarSiswa = $query->paginate(15)->withQueryString();

        $daftarRombel = Rombel::query()
            ->with('tahunAjaran')
            ->orderByDesc('id')
            ->get();

        return view('operator.siswa.index', [
            'daftarSiswa' => $daftarSiswa,
            'daftarRombel' => $daftarRombel,
            'opsiStatusData' => StatusDataSiswa::opsi(),
            'filter' => [
                'q' => $cari,
                'status_data' => $statusData,
                'rombel_id' => is_numeric($rombelId) ? (int) $rombelId : null,
            ],
        ]);
    }

    public function create(): View
    {
        return view('operator.siswa.form', [
            'siswa' => null,
            ...$this->dataPendukungForm(),
        ]);
    }

    public function store(
        SimpanSiswaRequest $request,
        SimpanSiswa $simpanSiswa,
        VerifikasiSiswa $verifikasiSiswa,
        AturRombelSiswa $aturRombelSiswa,
        KelolaAkunSiswa $kelolaAkunSiswa,
    ): RedirectResponse {
        $berkas = $this->ambilBerkas($request);
        $siswa = $simpanSiswa->handle($request->validated(), null, $berkas);

        $this->sinkronkanRombelDariRequest($request, $siswa, $aturRombelSiswa);

        $flash = ['status' => 'Draf data siswa berhasil disimpan.'];

        if ($request->boolean('buat_akun')) {
            $hasilAkun = $kelolaAkunSiswa->buatAtauResetPin($siswa, $request->string('pin')->toString() ?: null);
            $flash['pin_baru'] = $hasilAkun['pin_terang'];
            $flash['akun_username'] = $hasilAkun['pengguna']->username;
        }

        if ($request->input('aksi') === 'verifikasi') {
            $siswa = $verifikasiSiswa->handle($siswa);
            $flash['status'] = 'Data siswa berhasil disimpan dan diverifikasi.';
        }

        return redirect()
            ->route('operator.siswa.edit', $siswa)
            ->with($flash);
    }

    public function edit(Siswa $siswa): View
    {
        $siswa->load([
            'statusMasuk',
            'statusKesiswaan',
            'pekerjaanAyah',
            'pengguna',
            'berkas',
            'keanggotaanRombel.rombel.tahunAjaran',
        ]);

        return view('operator.siswa.form', [
            'siswa' => $siswa,
            ...$this->dataPendukungForm(),
        ]);
    }

    public function update(
        SimpanSiswaRequest $request,
        Siswa $siswa,
        SimpanSiswa $simpanSiswa,
        AturRombelSiswa $aturRombelSiswa,
    ): RedirectResponse {
        $berkas = $this->ambilBerkas($request);
        $simpanSiswa->handle($request->validated(), $siswa, $berkas);

        $this->sinkronkanRombelDariRequest($request, $siswa, $aturRombelSiswa);

        return redirect()
            ->route('operator.siswa.edit', $siswa)
            ->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function verifikasi(
        VerifikasiSiswaRequest $request,
        Siswa $siswa,
        VerifikasiSiswa $verifikasiSiswa,
    ): RedirectResponse {
        $berkas = $this->ambilBerkas($request);
        $verifikasiSiswa->handle($siswa, $request->validated(), $berkas);

        return redirect()
            ->route('operator.siswa.edit', $siswa)
            ->with('status', 'Status data siswa berhasil diverifikasi.');
    }

    public function aturRombel(
        Request $request,
        Siswa $siswa,
        AturRombelSiswa $aturRombelSiswa,
    ): RedirectResponse {
        $validated = $request->validate([
            'rombel_id' => ['required', 'integer', 'exists:rombel,id'],
        ]);

        /** @var Rombel $rombel */
        $rombel = Rombel::query()->findOrFail((int) $validated['rombel_id']);
        $aturRombelSiswa->handle($siswa, $rombel);

        return redirect()
            ->route('operator.siswa.edit', $siswa)
            ->with('status', 'Rombel siswa berhasil diperbarui.');
    }

    public function buatAkun(
        Request $request,
        Siswa $siswa,
        KelolaAkunSiswa $kelolaAkunSiswa,
    ): RedirectResponse {
        $validated = $request->validate([
            'pin' => ['nullable', 'digits:6'],
        ]);

        $pinInput = isset($validated['pin']) && is_string($validated['pin']) && $validated['pin'] !== ''
            ? $validated['pin']
            : null;

        $hasil = $kelolaAkunSiswa->buatAtauResetPin($siswa, $pinInput);

        return redirect()
            ->route('operator.siswa.edit', $siswa)
            ->with([
                'status' => 'Akun siswa berhasil dibuat/diaktifkan.',
                'pin_baru' => $hasil['pin_terang'],
                'akun_username' => $hasil['pengguna']->username,
            ]);
    }

    public function resetPin(
        Request $request,
        Siswa $siswa,
        KelolaAkunSiswa $kelolaAkunSiswa,
    ): RedirectResponse {
        $validated = $request->validate([
            'pin' => ['nullable', 'digits:6'],
        ]);

        $pinInput = isset($validated['pin']) && is_string($validated['pin']) && $validated['pin'] !== ''
            ? $validated['pin']
            : null;

        $hasil = $kelolaAkunSiswa->buatAtauResetPin($siswa, $pinInput);

        return redirect()
            ->route('operator.siswa.edit', $siswa)
            ->with([
                'status' => 'PIN akun siswa berhasil direset.',
                'pin_baru' => $hasil['pin_terang'],
                'akun_username' => $hasil['pengguna']->username,
            ]);
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return redirect()
            ->route('operator.siswa.index')
            ->with('status', 'Data siswa berhasil dihapus.');
    }

    /**
     * @return array<string, UploadedFile|null>
     */
    private function ambilBerkas(Request $request): array
    {
        $hasil = [];
        foreach (['pasfoto', 'kartu_keluarga', 'akta_lahir'] as $field) {
            $file = $request->file($field);
            $hasil[$field] = $file instanceof UploadedFile ? $file : null;
        }

        return $hasil;
    }

    private function sinkronkanRombelDariRequest(
        Request $request,
        Siswa $siswa,
        AturRombelSiswa $aturRombelSiswa,
    ): void {
        $rombelId = $request->input('rombel_id');
        if (! is_numeric($rombelId) || (int) $rombelId <= 0) {
            return;
        }

        $rombel = Rombel::query()->find((int) $rombelId);
        if ($rombel instanceof Rombel) {
            $aturRombelSiswa->handle($siswa, $rombel);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function dataPendukungForm(): array
    {
        return [
            'opsiJenisKelamin' => JenisKelamin::opsi(),
            'opsiStatusMasuk' => Referensi::query()->kelompok('status_masuk')->get(),
            'opsiStatusKesiswaan' => Referensi::query()->kelompok('status_kesiswaan')->get(),
            'opsiPekerjaan' => Referensi::query()->kelompok('pekerjaan')->get(),
            'daftarRombel' => Rombel::query()
                ->with('tahunAjaran')
                ->orderByDesc('id')
                ->get(),
            'tahunAjaranAktif' => TahunAjaran::query()->aktif()->first(),
        ];
    }
}
