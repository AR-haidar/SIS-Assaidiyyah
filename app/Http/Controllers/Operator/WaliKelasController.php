<?php

namespace App\Http\Controllers\Operator;

use App\Actions\Operator\AturWaliKelas;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\AturWaliKelasRequest;
use App\Models\Pegawai;
use App\Models\Rombel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaliKelasController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAjaranId = $request->query('tahun_ajaran_id');

        $rombelList = Rombel::query()
            ->with(['tahunAjaran', 'waliKelas.pengguna'])
            ->when(
                filled($tahunAjaranId),
                fn ($query) => $query->where('tahun_ajaran_id', (int) $tahunAjaranId)
            )
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $daftarPegawai = Pegawai::query()
            ->with('pengguna')
            ->orderBy('nama_lengkap')
            ->get();

        return view('operator.wali-kelas.index', [
            'rombelList' => $rombelList,
            'daftarPegawai' => $daftarPegawai,
        ]);
    }

    public function update(AturWaliKelasRequest $request, Rombel $rombel, AturWaliKelas $aksi): RedirectResponse
    {
        $waliKelasId = $request->validated('wali_kelas_id');
        $pegawaiBaru = $waliKelasId !== null
            ? Pegawai::query()->findOrFail((int) $waliKelasId)
            : null;

        $aksi->handle($rombel, $pegawaiBaru);

        return back()->with('status', "Penugasan wali kelas untuk rombel {$rombel->nama} berhasil diperbarui.");
    }
}
