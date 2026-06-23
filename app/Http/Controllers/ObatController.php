<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::latest()->paginate(10);
        $obatMenipis = Obat::hampirHabis()->count();

        return view('dashboard.obat.index', compact('obat', 'obatMenipis'));
    }

    public function show(Obat $obat)
    {
        return view('dashboard.obat.show', compact('obat'));
    }

    public function create()
    {
        return view('dashboard.obat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        if ((int) $validated['stok'] < (int) $validated['stok_minimum']) {
            return back()->withInput()->withErrors([
                'stok' => 'Stok obat tidak boleh kurang dari stok minimum.',
                'stok_minimum' => 'Stok minimum tidak boleh lebih dari stok.',
            ]);
        }

        Obat::create($validated);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function edit(Obat $obat)
    {
        return view('dashboard.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        if ((int) $validated['stok'] < (int) $validated['stok_minimum']) {
            return back()->withInput()->withErrors([
                'stok' => 'Stok obat tidak boleh kurang dari stok minimum.',
                'stok_minimum' => 'Stok minimum tidak boleh lebih dari stok.',
            ]);
        }

        $obat->update($validated);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus.');
    }

    public function exportCsv()
    {
        $obat = Obat::orderBy('nama_obat')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="data-obat-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($obat) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Nama Obat', 'Stok', 'Stok Minimum']);

            foreach ($obat as $o) {
                fputcsv($file, [
                    $o->nama_obat,
                    $o->stok,
                    $o->stok_minimum,
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
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== false) {
                if (! isset($row[0]) || $row[0] === '') continue;

                Obat::create([
                    'nama_obat' => trim($row[0]),
                    'stok' => (int) ($row[1] ?? 0),
                    'stok_minimum' => (int) ($row[2] ?? 0),
                ]);
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($file);
            return back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }

        fclose($file);

        return redirect()->route('obat.index')
            ->with('success', "Berhasil mengimpor {$imported} data obat.");
    }
}
