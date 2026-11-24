<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\MisiSekolah;
use App\Models\PesanKontak;
use App\Models\PrestasiSekolah;
use App\Models\ProfilGuruPublik;
use App\Models\ProgramUnggulan;
use App\Models\Sekolah;
use App\Support\PortalPublik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BerandaController extends Controller
{
    public function index(Request $request): View
    {
        $profilGuruPublik = Schema::hasTable('profil_guru_publik') ? ProfilGuruPublik::query()
            ->with(['pegawai', 'foto'])
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get() : collect();

        $prestasiSekolah = Schema::hasTable('prestasi_sekolah') ? PrestasiSekolah::query()
            ->with('foto')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get() : collect();

        return view('home', [
            'portal' => route(PortalPublik::rute($request)),
            'sekolah' => Schema::hasTable('sekolah') ? Sekolah::with('fotoGedung')->first() : null,
            'misiSekolah' => Schema::hasTable('misi_sekolah') ? MisiSekolah::orderBy('urutan')->get() : collect(),
            'programUnggulan' => Schema::hasTable('program_unggulan') ? ProgramUnggulan::with('foto')
                ->where('aktif', true)->orderBy('urutan')->orderBy('id')->get() : collect(),
            'profilGuruPublik' => $profilGuruPublik,
            'prestasiSekolah' => $prestasiSekolah,
        ]);
    }

    public function kirimPesan(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_pengirim' => ['required', 'string', 'max:150'],
            'kontak' => ['required', 'string', 'max:100'],
            'subjek' => ['required', 'string', 'max:150'],
            'pesan' => ['required', 'string', 'max:3000'],
        ], [
            'required' => ':Attribute wajib diisi.',
            'max' => ':Attribute maksimal :max karakter.',
        ], [
            'nama_pengirim' => 'nama lengkap',
            'kontak' => 'email atau kontak aktif',
        ]);

        $kembali = redirect()->route('publik.beranda')->withFragment('kontak');

        if ($validator->fails()) {
            return $kembali->withInput()->withErrors($validator, 'kontak');
        }

        $data = $validator->validated();
        PesanKontak::create([
            'nama_pengirim' => $data['nama_pengirim'],
            'kontak' => $data['kontak'],
            'isi_pesan' => 'Subjek: '.$data['subjek']."\n\n".$data['pesan'],
        ]);

        return $kembali->with('pesan_kontak_status', 'Pesan Anda berhasil diterima.');
    }

    public function foto(string $jenis, int $id): StreamedResponse
    {
        $berkas = match ($jenis) {
            'gedung' => Sekolah::findOrFail($id)->fotoGedung,
            'program' => ProgramUnggulan::where('aktif', true)->findOrFail($id)->foto,
            'guru' => ProfilGuruPublik::where('aktif', true)->findOrFail($id)->foto,
            'prestasi' => PrestasiSekolah::where('aktif', true)->findOrFail($id)->foto,
            default => null,
        };

        abort_unless($berkas instanceof Berkas && in_array($berkas->mime, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        $disk = Storage::disk('local');
        abort_unless($disk->fileExists($berkas->path), 404);

        return $disk->response($berkas->path, null, [
            'Content-Type' => $berkas->mime,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
