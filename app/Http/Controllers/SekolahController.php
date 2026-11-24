<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SekolahController extends Controller
{
    public function showKepala(): View
    {
        return view('kepala-sekolah.data-sekolah', [
            'sekolah' => Sekolah::query()->first(),
        ]);
    }

    public function showHumas(): View
    {
        return view('humas.data-sekolah', [
            'sekolah' => Sekolah::query()->first(),
        ]);
    }

    public function editOperator(): View
    {
        return view('operator.data-sekolah', [
            'sekolah' => Sekolah::query()->first(),
        ]);
    }

    public function updateOperator(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:200'],
            'npsn' => ['nullable', 'string', 'max:20'],
            'nsm' => ['nullable', 'string', 'max:20'],
            'status_sekolah' => ['nullable', 'string', 'max:100'],
            'akreditasi' => ['nullable', 'string', 'max:50'],
            'yayasan' => ['nullable', 'string', 'max:200'],
            'nomor_sk_operasional' => ['nullable', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'kelurahan' => ['nullable', 'string', 'max:120'],
            'kecamatan' => ['nullable', 'string', 'max:120'],
            'kota_kabupaten' => ['nullable', 'string', 'max:120'],
            'provinsi' => ['nullable', 'string', 'max:120'],
            'kode_pos' => ['nullable', 'string', 'max:20'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'website' => ['nullable', 'string', 'max:200'],
        ]);

        $sekolah = Sekolah::query()->first();

        if ($sekolah instanceof Sekolah) {
            $sekolah->update($validated);
        } else {
            Sekolah::query()->create($validated);
        }

        return back()->with('status', 'Data pokok madrasah berhasil diperbarui.');
    }
}
