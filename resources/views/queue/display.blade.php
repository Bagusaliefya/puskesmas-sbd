<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Antrian — {{ config('app.name') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; overflow: hidden; }
        .container { max-width: 1400px; margin: 0 auto; padding: 40px; height: 100vh; display: flex; flex-direction: column; }
        header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 40px; flex-shrink: 0; }
        .brand { font-size: 2rem; font-weight: 800; color: #38bdf8; letter-spacing: -0.02em; }
        .brand span { color: #64748b; font-weight: 400; }
        .clock { font-size: 1.5rem; font-weight: 600; color: #94a3b8; font-variant-numeric: tabular-nums; }
        .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; flex: 1; min-height: 0; }
        .now-serving { background: linear-gradient(135deg, #1e293b, #0f172a); border: 1px solid #334155; border-radius: 24px; padding: 40px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
        .now-serving .label { font-size: 1.25rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; }
        .now-serving .name { font-size: 4rem; font-weight: 900; color: #f8fafc; line-height: 1.1; }
        .now-serving .no-queue { font-size: 3rem; font-weight: 700; color: #475569; }
        .now-serving .status-badge { margin-top: 20px; display: inline-flex; align-items: center; gap: 8px; padding: 8px 24px; border-radius: 999px; background: #38bdf8 / .15; color: #38bdf8; font-weight: 600; font-size: 1rem; }
        .waiting-list { background: #1e293b; border: 1px solid #334155; border-radius: 24px; padding: 32px; display: flex; flex-direction: column; overflow: hidden; }
        .waiting-list h2 { font-size: 1.25rem; font-weight: 700; color: #94a3b8; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .waiting-items { flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; }
        .waiting-items::-webkit-scrollbar { width: 4px; }
        .waiting-items::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .waiting-item { display: flex; align-items: center; gap: 16px; padding: 12px 16px; background: #0f172a; border-radius: 12px; }
        .waiting-item .number { font-size: 1.5rem; font-weight: 800; color: #475569; min-width: 48px; }
        .waiting-item .nama { font-size: 1.25rem; font-weight: 600; color: #e2e8f0; }
        .waiting-item .time { margin-left: auto; font-size: 0.875rem; color: #64748b; }
        .done-section { margin-top: 24px; padding-top: 24px; border-top: 1px solid #334155; flex-shrink: 0; }
        .done-section h3 { font-size: 1rem; font-weight: 600; color: #475569; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
        .done-items { display: flex; flex-wrap: wrap; gap: 8px; }
        .done-item { padding: 6px 16px; background: #0f172a; border-radius: 999px; font-size: 0.875rem; color: #475569; }
        footer { text-align: center; padding-top: 20px; color: #334155; font-size: 0.875rem; flex-shrink: 0; }
        @media (max-width: 1024px) {
            .main-grid { grid-template-columns: 1fr; }
            .now-serving .name { font-size: 2.5rem; }
            body { overflow: auto; }
            .container { height: auto; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="brand">{{ config('app.name') }} <span>| Antrian</span></div>
            <div class="clock" id="clock"></div>
        </header>

        <div class="main-grid">
            <div class="now-serving">
                @if($sedangDipanggil)
                    <div class="label"><span class="material-symbols-outlined" style="font-size:1.5rem;vertical-align:middle">campaign</span> Sedang Dipanggil</div>
                    <div class="name">{{ $sedangDipanggil->pasien->nama_pasien ?? '-' }}</div>
                    <div class="status-badge">
                        <span class="material-symbols-outlined">check_circle</span>
                        Dipanggil {{ $sedangDipanggil->dipanggil_at->format('H:i') }}
                    </div>
                @elseif($antrean->count() > 0)
                    <div class="label">Selanjutnya</div>
                    <div class="name">{{ $antrean->first()->pasien->nama_pasien ?? '-' }}</div>
                    <div class="status-badge">
                        <span class="material-symbols-outlined">hourglass_top</span>
                        Menunggu Panggilan
                    </div>
                @else
                    <div class="no-queue">Tidak Ada Antrian</div>
                    <div class="status-badge" style="background:#22c55e / .15;color:#22c55e">
                        <span class="material-symbols-outlined">check_circle</span>
                        Semua Pasien Sudah Diperiksa
                    </div>
                @endif
            </div>

            <div class="waiting-list">
                <h2><span class="material-symbols-outlined">pending_actions</span> Menunggu ({{ $antrean->count() }})</h2>

                <div class="waiting-items">
                    @forelse($antrean as $i => $a)
                    <div class="waiting-item">
                        <div class="number">{{ $i + 1 }}</div>
                        <div class="nama">{{ $a->pasien->nama_pasien ?? '-' }}</div>
                        <div class="time">{{ \Carbon\Carbon::parse($a->created_at)->format('H:i') }}</div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:40px;color:#475569;font-size:1.25rem">
                        <span class="material-symbols-outlined" style="font-size:3rem;display:block;margin-bottom:12px">check</span>
                        Semua pasien sudah dilayani
                    </div>
                    @endforelse
                </div>

                @if($selesai->count() > 0)
                <div class="done-section">
                    <h3><span class="material-symbols-outlined">history</span> Selesai Diperiksa</h3>
                    <div class="done-items">
                        @foreach($selesai as $s)
                        <span class="done-item">{{ $s->pasien->nama_pasien ?? '-' }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <footer>
            <span class="material-symbols-outlined" style="font-size:12px;vertical-align:middle">refresh</span> Antrian diperbarui secara real-time
        </footer>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Asia/Jakarta' };
            document.getElementById('clock').textContent = now.toLocaleDateString('id-ID', opts);
        }
        updateClock();
        setInterval(updateClock, 1000);

        let polling = false;
        async function refreshAntrian() {
            if (polling) return;
            polling = true;
            try {
                const res = await fetch('{{ route("antrian.json") }}');
                const data = await res.json();
                if (!data.success) return;

                const mainGrid = document.querySelector('.main-grid');
                const nowServing = mainGrid.querySelector('.now-serving');
                const waitingList = mainGrid.querySelector('.waiting-list');

                const sd = data.data.sedang_dipanggil;
                if (sd) {
                    nowServing.innerHTML = `
                        <div class="label"><span class="material-symbols-outlined" style="font-size:1.5rem;vertical-align:middle">campaign</span> Sedang Dipanggil</div>
                        <div class="name">${sd.nama_pasien}</div>
                        <div class="status-badge">
                            <span class="material-symbols-outlined">check_circle</span>
                            Dipanggil ${sd.dipanggil_at}
                        </div>`;
                } else if (data.data.antrean.length > 0) {
                    nowServing.innerHTML = `
                        <div class="label">Selanjutnya</div>
                        <div class="name">${data.data.antrean[0].nama_pasien}</div>
                        <div class="status-badge">
                            <span class="material-symbols-outlined">hourglass_top</span>
                            Menunggu Panggilan
                        </div>`;
                } else {
                    nowServing.innerHTML = `
                        <div class="no-queue">Tidak Ada Antrian</div>
                        <div class="status-badge" style="background:#22c55e / .15;color:#22c55e">
                            <span class="material-symbols-outlined">check_circle</span>
                            Semua Pasien Sudah Diperiksa
                        </div>`;
                }

                const antrean = data.data.antrean;
                let waitingHtml = `<h2><span class="material-symbols-outlined">pending_actions</span> Menunggu (${antrean.length})</h2>
                    <div class="waiting-items">`;

                if (antrean.length === 0) {
                    waitingHtml += `<div style="text-align:center;padding:40px;color:#475569;font-size:1.25rem">
                        <span class="material-symbols-outlined" style="font-size:3rem;display:block;margin-bottom:12px">check</span>
                        Semua pasien sudah dilayani</div>`;
                } else {
                    antrean.forEach((a, i) => {
                        waitingHtml += `<div class="waiting-item">
                            <div class="number">${i + 1}</div>
                            <div class="nama">${a.nama_pasien}</div>
                            <div class="time">${a.created_at}</div>
                        </div>`;
                    });
                }
                waitingHtml += `</div>`;

                const selesai = data.data.selesai;
                if (selesai && selesai.length > 0) {
                    waitingHtml += `<div class="done-section">
                        <h3><span class="material-symbols-outlined">history</span> Selesai Diperiksa</h3>
                        <div class="done-items">`;
                    selesai.forEach(s => {
                        waitingHtml += `<span class="done-item">${s.nama_pasien}</span>`;
                    });
                    waitingHtml += `</div></div>`;
                }

                waitingList.innerHTML = waitingHtml;
            } catch (e) {
                console.error('Refresh gagal', e);
            }
            polling = false;
        }

        setInterval(refreshAntrian, 10000);
    </script>
</body>
</html>
