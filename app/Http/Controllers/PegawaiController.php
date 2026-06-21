<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Pegawai;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PegawaiController extends Controller
{
    public function index()
    {
        $petugas = Petugas::with('pegawai')->get();
        $dokter = Dokter::with('pegawai')->get();

        return view('dashboard.pegawai.index', compact('petugas', 'dokter'));
    }

    public function create()
    {
        return view('dashboard.pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_masuk' => 'nullable|date',
            'alamat' => 'nullable|string',
            'tipe' => 'required|in:petugas,dokter',
            'password' => 'required|string|min:3',
            'loket' => 'nullable|string|max:255',
            'spesialisasi' => 'nullable|string|max:255',
        ]);

        $pegawai = Pegawai::create([
            'nama_pegawai' => $validated['nama_pegawai'],
            'jabatan' => $validated['jabatan'],
            'no_hp' => $validated['no_hp'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'alamat' => $validated['alamat'],
        ]);

        if ($validated['tipe'] === 'petugas') {
            Petugas::create([
                'id_pegawai' => $pegawai->id_pegawai,
                'loket' => $validated['loket'],
            ]);
        } else {
            Dokter::create([
                'id_pegawai' => $pegawai->id_pegawai,
                'spesialisasi' => $validated['spesialisasi'],
            ]);
        }

        $baseEmail = Str::of($validated['nama_pegawai'])->lower()->replace(' ', '.')->append('@puskesmas.test')->toString();
        $email = $baseEmail;
        $suffix = 1;
        while (User::where('email', $email)->exists()) {
            $email = Str::of($baseEmail)->replace('@puskesmas.test', "{$suffix}@puskesmas.test")->toString();
            $suffix++;
        }

        $user = new User([
            'name' => $validated['nama_pegawai'],
            'email' => $email,
            'password' => $validated['password'],
            'id_pegawai' => $pegawai->id_pegawai,
        ]);
        $user->role = $validated['tipe'];
        $user->save();
        $user->assignRole($validated['tipe']);

        return redirect()->route('pegawai.index')->with('success', "Data pegawai berhasil ditambahkan. Akun login: {$email}");
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load(['petugas', 'dokter']);

        return view('dashboard.pegawai.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai)
    {
        $pegawai->load(['petugas', 'dokter']);

        return view('dashboard.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_masuk' => 'nullable|date',
            'alamat' => 'nullable|string',
            'tipe' => 'required|in:petugas,dokter',
            'password' => 'nullable|string|min:3',
            'loket' => 'nullable|string|max:255',
            'spesialisasi' => 'nullable|string|max:255',
        ]);

        $pegawai->update([
            'nama_pegawai' => $validated['nama_pegawai'],
            'jabatan' => $validated['jabatan'],
            'no_hp' => $validated['no_hp'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'alamat' => $validated['alamat'],
        ]);

        if ($validated['tipe'] === 'petugas') {
            $pegawai->petugas()->updateOrCreate(
                ['id_pegawai' => $pegawai->id_pegawai],
                ['loket' => $validated['loket']]
            );
            $pegawai->dokter()->delete();
        } else {
            $pegawai->dokter()->updateOrCreate(
                ['id_pegawai' => $pegawai->id_pegawai],
                ['spesialisasi' => $validated['spesialisasi']]
            );
            $pegawai->petugas()->delete();
        }

        $user = User::where('id_pegawai', $pegawai->id_pegawai)->first();
        if ($user) {
            $user->name = $validated['nama_pegawai'];
            $user->role = $validated['tipe'];
            if ($request->filled('password')) {
                $user->password = $validated['password'];
            }
            $user->save();
            $user->syncRoles([$validated['tipe']]);
        }

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        User::where('id_pegawai', $pegawai->id_pegawai)->delete();
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
