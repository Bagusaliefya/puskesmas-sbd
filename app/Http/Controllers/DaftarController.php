<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class DaftarController extends Controller
{
    public function landing()
    {
        $status = statusPuskesmas();
        return view('landing', compact('status'));
    }

    public function form()
    {
        $status = statusPuskesmas();
        return view('daftar', compact('status'));
    }

    public function submit(Request $request)
    {
        $status = statusPuskesmas();
        if (! $status['bisa_daftar']) {
            return back()->with('error', $status['pesan']);
        }

        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|string|max:5',
            'keluhan' => 'nullable|string',
        ]);

        $pasien = Pasien::create([
            'nama_pasien' => $validated['nama_pasien'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'golongan_darah' => $validated['golongan_darah'],
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_pasien' => $pasien->id_pasien,
            'tipe_pendaftaran' => 'mandiri',
            'tanggal_daftar' => today(),
            'keluhan' => $validated['keluhan'],
        ]);

        return redirect()->route('daftar.sukses', $pendaftaran->id_pendaftaran);
    }

    public function sukses($id)
    {
        $pendaftaran = Pendaftaran::with('pasien')->findOrFail($id);

        return view('daftar-sukses', compact('pendaftaran'));
    }
}
