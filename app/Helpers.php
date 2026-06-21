<?php

use Illuminate\Support\Carbon;

if (! function_exists('statusPuskesmas')) {
    function statusPuskesmas(): array
    {
        $now = Carbon::now();
        $waktu = $now->format('H:i');

        $buka = config('puskesmas.jam_buka');
        $istirahatMulai = config('puskesmas.jam_istirahat_mulai');
        $istirahatSelesai = config('puskesmas.jam_istirahat_selesai');
        $tutup = config('puskesmas.jam_tutup');

        if ($waktu < $buka || $waktu >= $tutup) {
            return [
                'status' => 'tutup',
                'label' => 'Tutup',
                'warna' => 'oklch(72% 0.166 70)',
                'icon' => 'cancel',
                'pesan' => "Puskesmas tutup. Buka pukul {$buka} WIB.",
                'bisa_daftar' => false,
                'bisa_periksa' => false,
            ];
        }

        if ($waktu >= $istirahatMulai && $waktu < $istirahatSelesai) {
            return [
                'status' => 'istirahat',
                'label' => 'Istirahat',
                'warna' => 'oklch(72% 0.166 70)',
                'icon' => 'free_breakfast',
                'pesan' => "Jam istirahat. Buka kembali pukul {$istirahatSelesai} WIB.",
                'bisa_daftar' => true,
                'bisa_periksa' => false,
            ];
        }

        return [
            'status' => 'buka',
            'label' => 'Buka',
            'warna' => 'oklch(52% 0.1 182)',
            'icon' => 'check_circle',
            'pesan' => "Puskesmas buka. Tutup pukul {$tutup} WIB.",
            'bisa_daftar' => true,
            'bisa_periksa' => true,
        ];
    }
}
