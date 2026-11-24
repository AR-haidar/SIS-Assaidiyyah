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
        $cari = trim((string) $request->query('q', $request->query('search', '')));
        $statusFilter = trim((string) $request->query('status_data', ''));
        $tingkatFilter = trim((string) $request->query('kelas', ''));
        $rombelNamaFilter = trim((string) $request->query('rombel', ''));

        $daftarSiswa = Siswa::query()
            ->with([
                'keanggotaanRombel.rombel.tahunAjaran',
                'pengguna',
            ])
            ->when($statusFilter !== '', fn ($q) => $q->where('status_data', $statusFilter))
            ->when($tingkatFilter !== '' && is_numeric($tingkatFilter), function ($q) use ($tingkatFilter): void {
                $q->whereHas('keanggotaanRombel.rombel', fn ($sub) => $sub->where('tingkat', (int) $tingkatFilter));
            })
            ->when($rombelNamaFilter !== '', function ($q) use ($rombelNamaFilter): void {
                $q->whereHas('keanggotaanRombel.rombel', fn ($sub) => $sub->where('nama', 'like', '%'.$rombelNamaFilter.'%'));
            })
            ->when($cari !== '', function ($q) use ($cari): void {
                $q->where(function ($sub) use ($cari): void {
                    $sub->where('nama_lengkap', 'like', '%'.$cari.'%')
                        ->orWhere('nisn', 'like', '%'.$cari.'%')
                        ->orWhere('nik', 'like', '%'.$cari.'%');
                });
            })
            ->orderBy('nama_lengkap')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $statistik = [
            'total' => Siswa::query()->count(),
            'laki_laki' => Siswa::query()->where('jenis_kelamin', JenisKelamin::L->value)->count(),
            'perempuan' => Siswa::query()->where('jenis_kelamin', JenisKelamin::P->value)->count(),
        ];

        return view('operator.data-siswa', [
            'daftarSiswa' => $daftarSiswa,
            'statistik' => $statistik,
            'opsiStatusData' => StatusDataSiswa::opsi(),
            'filter' => [
                'q' => $cari,
                'status_data' => $statusFilter,
                'kelas' => $tingkatFilter,
                'rombel' => $rombelNamaFilter,
            ],
        ]);
    }

    public function create(): View
    {
        return view('operator.tambah-siswa', [
            'siswa' => new Siswa(['status_data' => StatusDataSiswa::Draft]),
            ...$this->dataFormulir(),
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

        if ($request->input('status_data') === StatusDataSiswa::Terverifikasi->value || $request->input('aksi') === 'verifikasi') {
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
            'keanggotaanRombel.rombel.tahunAjaran',
            'berkas',
            'pengguna',
        ]);

        return view('operator.edit-siswa', [
            'siswa' => $siswa,
            ...$this->dataFormulir(),
        ]);
    }

    public function update(
        SimpanSiswaRequest $request,
        Siswa $siswa,
        SimpanSiswa $simpanSiswa,
        VerifikasiSiswa $verifikasiSiswa,
        AturRombelSiswa $aturRombelSiswa,
    ): RedirectResponse {
        $berkas = $this->ambilBerkas($request);
        $simpanSiswa->handle($request->validated(), $siswa, $berkas);

        $this->sinkronkanRombelDariRequest($request, $siswa, $aturRombelSiswa);

        $flash = ['status' => 'Data siswa berhasil diperbarui.'];

        if ($request->input('status_data') === StatusDataSiswa::Terverifikasi->value || $request->input('aksi') === 'verifikasi') {
            $verifikasiSiswa->handle($siswa);
            $flash['status'] = 'Data siswa berhasil diperbarui dan diverifikasi.';
        }

        return redirect()
            ->route('operator.siswa.edit', $siswa)
            ->with($flash);
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

        return back()->with('status', 'Rombel siswa berhasil diperbarui.');
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

        return back()->with([
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
            'pin_baru' => ['nullable', 'digits:6'],
        ]);

        $pinRaw = $validated['pin_baru'] ?? $validated['pin'] ?? null;
        $pinInput = is_string($pinRaw) && $pinRaw !== '' ? $pinRaw : null;

        $hasil = $kelolaAkunSiswa->buatAtauResetPin($siswa, $pinInput);

        return back()->with([
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
            ->with('status', 'Data siswa berhasil diarsipkan.');
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
    private function dataFormulir(): array
    {
        return [
            'opsiJenisKelamin' => JenisKelamin::opsi(),
            'opsiStatusData' => StatusDataSiswa::opsi(),
            'opsiAgama' => Referensi::query()->kelompok('agama')->get(),
            'opsiKebutuhanKhusus' => Referensi::query()->kelompok('kebutuhan_khusus')->get(),
            'opsiDisabilitas' => Referensi::query()->kelompok('disabilitas')->get(),
            'opsiPekerjaan' => Referensi::query()->kelompok('pekerjaan')->get(),
            'opsiJenisBerkasSiswa' => Referensi::query()->kelompok('jenis_berkas_siswa')->get(),
            'daftarTahunAjaran' => TahunAjaran::query()->orderByDesc('aktif')->orderByDesc('kode')->get(),
            'daftarRombel' => Rombel::query()->with('tahunAjaran')->orderBy('tingkat')->orderBy('nama')->get(),
        ];
    }
}
