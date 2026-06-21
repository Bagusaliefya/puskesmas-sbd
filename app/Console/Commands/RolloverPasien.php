<?php

namespace App\Console\Commands;

use App\Models\Pendaftaran;
use Illuminate\Console\Command;

class RolloverPasien extends Command
{
    protected $signature = 'puskesmas:rollover';
    protected $description = 'Rollover pasien yang belum diperiksa ke hari ini';

    public function handle(): int
    {
        $pending = Pendaftaran::whereDate('tanggal_daftar', '<', today())
            ->doesntHave('pemeriksaan')
            ->orderBy('tanggal_daftar')
            ->orderBy('no_antrian')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('Tidak ada pasien yang perlu dirollover.');
            return self::SUCCESS;
        }

        $lastAntrian = Pendaftaran::whereDate('tanggal_daftar', today())->max('no_antrian') ?? 0;

        foreach ($pending as $i => $p) {
            $p->update([
                'tanggal_daftar' => today(),
                'no_antrian' => $lastAntrian + 1 + $i,
                'dipanggil_at' => null,
                'id_dokter' => null,
            ]);
        }

        $this->info("Berhasil rollover {$pending->count()} pasien ke hari ini.");
        return self::SUCCESS;
    }
}
