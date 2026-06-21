@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold flex items-center gap-2" style="color:oklch(12% 0.028 262)">
        <span class="material-symbols-outlined" style="color:oklch(42% 0.086 187)">manage_accounts</span>
        Manajemen User
    </h1>
</div>

<div class="tonal-card overflow-hidden">
    <div class="p-1">
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Pegawai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td class="font-medium">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge badge-sm {{ $u->role === 'admin' ? 'badge-warning' : ($u->role === 'dokter' ? 'badge-info' : 'badge-ghost') }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td>{{ $u->pegawai?->nama_pegawai ?? '-' }}</td>
                    <td>
                        @if($u->is_active)
                            <span class="badge badge-sm badge-success">Aktif</span>
                        @else
                            <span class="badge badge-sm badge-error">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        @if($u->id !== auth()->id())
                        <div class="flex gap-1 items-center" style="flex-wrap:wrap">
                            <button class="btn-secondary-action text-sm" onclick="document.getElementById('reset-{{ $u->id }}').showModal()">
                                <span class="material-symbols-outlined" style="font-size:16px">key</span> Reset
                            </button>
                            <button class="btn-{{ $u->is_active ? 'danger' : 'success' }}-action text-sm" onclick="document.getElementById('toggle-{{ $u->id }}').showModal()">
                                <span class="material-symbols-outlined" style="font-size:16px">{{ $u->is_active ? 'block' : 'check_circle' }}</span>
                                {{ $u->is_active ? 'Nonaktif' : 'Aktifkan' }}
                            </button>
                            <dialog id="toggle-{{ $u->id }}" class="modal">
                                <div class="modal-box text-center py-10 px-8">
                                    <div class="w-16 h-16 rounded-full bg-{{ $u->is_active ? 'error' : 'success' }}/10 flex items-center justify-center mx-auto mb-4">
                                        <span class="material-symbols-outlined text-4xl text-{{ $u->is_active ? 'error' : 'success' }}">{{ $u->is_active ? 'block' : 'check_circle' }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold mb-1">{{ $u->is_active ? 'Nonaktifkan User?' : 'Aktifkan User?' }}</h3>
                                    <p class="text-sm mb-6" style="color:oklch(20% 0.024 262 / .6)">Yakin ingin {{ $u->is_active ? 'menonaktifkan' : 'mengaktifkan' }} <strong>{{ $u->name }}</strong>?</p>
                                    <form method="POST" action="{{ route('user.toggle-active', $u) }}">
                                        @csrf
                                        <div class="flex justify-center gap-2">
                                            <button type="button" class="btn-ghost-action" onclick="document.getElementById('toggle-{{ $u->id }}').close()">Batal</button>
                                            <button class="btn-{{ $u->is_active ? 'danger' : 'success' }}-action">Ya, {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                        </div>
                                    </form>
                                </div>
                                <form method="dialog" class="modal-backdrop"><button></button></form>
                            </dialog>
                        </div>

                        <dialog id="reset-{{ $u->id }}" class="modal">
                            <div class="modal-box py-10 px-8">
                                <h3 class="text-lg font-bold mb-1">Reset Password</h3>
                                <p class="text-sm mb-6" style="color:oklch(20% 0.024 262 / .6)">Masukkan password baru untuk <strong>{{ $u->name }}</strong></p>
                                <form method="POST" action="{{ route('user.reset-password', $u) }}">
                                    @csrf
                                    <div class="form-control">
                                        <label class="label"><span class="label-text font-medium">Password Baru</span></label>
                                        <input type="password" name="password" class="input input-bordered w-full" minlength="8" required>
                                    </div>
                                    <div class="form-control mt-3">
                                        <label class="label"><span class="label-text font-medium">Konfirmasi Password</span></label>
                                        <input type="password" name="password_confirmation" class="input input-bordered w-full" required>
                                    </div>
                                    <div class="flex justify-center gap-2 mt-6">
                                        <button type="button" class="btn-ghost-action" onclick="document.getElementById('reset-{{ $u->id }}').close()">Batal</button>
                                        <button class="btn-primary-action">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <form method="dialog" class="modal-backdrop"><button></button></form>
                        </dialog>
                        @else
                        <span class="text-xs" style="color:oklch(20% 0.024 262 / .5)">Akun sendiri</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8" style="color:oklch(20% 0.024 262 / .5)">Belum ada data user</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>
<div class="mt-6">{{ $users->links() }}</div>
@endsection
