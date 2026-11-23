<?php

namespace App\Http\Controllers\Operator;

use App\Actions\Operator\HapusPegawai;
use App\Actions\Operator\SimpanPegawai;
use App\Enums\JenisAkun;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\SimpanPegawaiRequest;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Referensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $cari = trim((string) $request->query('cari', ''));
        $statusKepegawaianId = $request->query('status_kepegawaian_id');
        $jenisGtkId = $request->query('jenis_gtk_id');

        $pegawaiList = Pegawai::query()
            ->with(['pengguna', 'statusKepegawaian', 'jenisGtk', 'pasfoto'])
            ->when($cari !== '', function ($query) use ($cari): void {
                $query->where(function ($sub) use ($cari): void {
                    $sub->where('nama_lengkap', 'ilike', "%{$cari}%")
                        ->orWhere('nip', 'ilike', "%{$cari}%")
                        ->orWhere('nuptk', 'ilike', "%{$cari}%")
                        ->orWhere('nik', 'ilike', "%{$cari}%");
                });
            })
            ->when(
                filled($statusKepegawaianId),
                fn ($query) => $query->where('status_kepegawaian_id', (int) $statusKepegawaianId)
            )
            ->when(
                filled($jenisGtkId),
                fn ($query) => $query->where('jenis_gtk_id', (int) $jenisGtkId)
            )
            ->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        return view('operator.pegawai.index', [
            'pegawaiList' => $pegawaiList,
            'opsiStatusKepegawaian' => Referensi::query()->kelompok('status_kepegawaian')->get(),
            'opsiJenisGtk' => Referensi::query()->kelompok('jenis_gtk')->get(),
        ]);
    }

    public function create(): View
    {
        return view('operator.pegawai.form', [
            'pegawai' => new Pegawai,
            'opsiPendidikan' => Referensi::query()->kelompok('pendidikan_terakhir')->get(),
            'opsiStatusKepegawaian' => Referensi::query()->kelompok('status_kepegawaian')->get(),
            'opsiJenisGtk' => Referensi::query()->kelompok('jenis_gtk')->get(),
            'daftarAkunGuru' => Pengguna::query()
                ->where('jenis_akun', JenisAkun::Staf->value)
                ->whereHas('peran', fn ($q) => $q->where('kode', 'guru'))
                ->orderBy('username')
                ->get(),
        ]);
    }

    public function store(SimpanPegawaiRequest $request, SimpanPegawai $aksi): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('pasfoto')) {
            $data['pasfoto'] = $request->file('pasfoto');
        }

        $pegawai = $aksi->handle($data);

        return redirect()
            ->route('operator.pegawai.index')
            ->with('status', "Data pegawai {$pegawai->nama_lengkap} berhasil disimpan.");
    }

    public function edit(Pegawai $pegawai): View
    {
        $pegawai->load(['pasfoto', 'pengguna']);

        return view('operator.pegawai.form', [
            'pegawai' => $pegawai,
            'opsiPendidikan' => Referensi::query()->kelompok('pendidikan_terakhir')->get(),
            'opsiStatusKepegawaian' => Referensi::query()->kelompok('status_kepegawaian')->get(),
            'opsiJenisGtk' => Referensi::query()->kelompok('jenis_gtk')->get(),
            'daftarAkunGuru' => Pengguna::query()
                ->where('jenis_akun', JenisAkun::Staf->value)
                ->whereHas('peran', fn ($q) => $q->where('kode', 'guru'))
                ->orderBy('username')
                ->get(),
        ]);
    }

    public function update(SimpanPegawaiRequest $request, Pegawai $pegawai, SimpanPegawai $aksi): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('pasfoto')) {
            $data['pasfoto'] = $request->file('pasfoto');
        }

        $diperbarui = $aksi->handle($data, $pegawai);

        return redirect()
            ->route('operator.pegawai.index')
            ->with('status', "Data pegawai {$diperbarui->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(Pegawai $pegawai, HapusPegawai $aksi): RedirectResponse
    {
        $aksi->handle($pegawai);

        return redirect()
            ->route('operator.pegawai.index')
            ->with('status', 'Data pegawai berhasil dihapus.');
    }
}
