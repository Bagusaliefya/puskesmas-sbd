<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::latest()->paginate(10);

        return view('dashboard.pasien.index', compact('pasien'));
    }

    public function create()
    {
        return view('dashboard.pasien.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|string|max:5',
        ]);

        Pasien::create($validated);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show(Pasien $pasien)
    {
        $pasien->load('pendaftaran.petugas.pegawai', 'pendaftaran.pemeriksaan');

        return view('dashboard.pasien.show', compact('pasien'));
    }

    public function edit(Pasien $pasien)
    {
        return view('dashboard.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|string|max:5',
        ]);

        $pasien->update($validated);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }

    public function exportCsv()
    {
        $pasien = Pasien::orderBy('nama_pasien')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="data-pasien-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($pasien) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Nama', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat', 'No HP', 'Golongan Darah']);

            foreach ($pasien as $p) {
                fputcsv($file, [
                    $p->nama_pasien,
                    $p->tanggal_lahir ?? '',
                    $p->jenis_kelamin === 'L' ? 'Laki-laki' : ($p->jenis_kelamin === 'P' ? 'Perempuan' : ''),
                    $p->alamat ?? '',
                    $p->no_hp ?? '',
                    $p->golongan_darah ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');
        fgets($file);

        $imported = 0;
        while (($row = fgetcsv($file)) !== false) {
            if (empty($row[0])) continue;

            Pasien::create([
                'nama_pasien' => $row[0],
                'tanggal_lahir' => !empty($row[1]) ? $row[1] : null,
                'jenis_kelamin' => in_array($row[2] ?? '', ['L', 'P']) ? $row[2] : null,
                'alamat' => $row[3] ?? null,
                'no_hp' => $row[4] ?? null,
                'golongan_darah' => $row[5] ?? null,
            ]);
            $imported++;
        }

        fclose($file);

        return redirect()->route('pasien.index')
            ->with('success', "Berhasil mengimpor {$imported} data pasien.");
    }
}
