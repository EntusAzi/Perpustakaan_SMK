@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('page-title', 'Profil Pengguna')

@section('content')

<div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
    <div>
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-secondary); margin-bottom: 4px;">
            <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Dashboard</a>
            <span>/</span>
            <span style="color: var(--text-primary); font-weight: 600;">Profil Saya</span>
        </div>
        <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
            Kelola informasi pribadi, data kredensial, dan keamanan akun petugas perpustakaan.
        </p>
    </div>

    {{-- Tombol Edit Profil --}}
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('profil.edit') }}" class="btn btn-primary" style="box-shadow: 0 2px 6px rgba(0, 201, 167, 0.35);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit Profil Saya
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; align-items: start;">
    {{-- KOLOM KIRI: KARTU IDENTITAS PETUGAS --}}
    <div class="form-card" style="padding: 24px; text-align: center; margin-bottom: 0;">
        {{-- Avatar Pengguna --}}
        <div style="margin: 0 auto 16px; position: relative; width: 100px; height: 100px;">
            @if($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar))
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--teal); box-shadow: var(--shadow-md);">
            @else
                <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #00c9a7 0%, #008f77 100%); display: flex; align-items: center; justify-content: center; font-size: 38px; font-weight: 700; color: white; border: 3px solid #b2ede4; box-shadow: var(--shadow-md);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div style="position: absolute; bottom: 2px; right: 4px; width: 16px; height: 16px; background: #10b981; border: 2.5px solid white; border-radius: 50%;" title="Akun Aktif"></div>
        </div>

        <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">{{ $user->name }}</h2>
        <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">{{ '@' . $user->username }}</div>

        <div style="display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; margin-bottom: 18px;">
            <span class="badge" style="background: var(--teal-light); color: var(--teal-dark); border: 1px solid var(--teal-border); font-size: 11px;">
                {{ ucfirst($user->role ?? 'Pustakawan') }}
            </span>
            <span class="badge" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 11px;">
                {{ $user->jabatan ?? 'Petugas Perpustakaan' }}
            </span>
        </div>

        <div style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 12px; text-align: left; font-size: 13px;">
            <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px; color: var(--text-secondary);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--teal); flex-shrink: 0;">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $user->email }}</span>
            </div>
            <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px; color: var(--text-secondary);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--teal); flex-shrink: 0;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span>Bergabung: {{ $user->created_at ? $user->created_at->locale('id')->isoFormat('D MMMM YYYY') : '-' }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; color: var(--text-secondary);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--teal); flex-shrink: 0;">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span>Status: <strong style="color: #059669;">Terverifikasi Aktif</strong></span>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border); padding-top: 18px; margin-top: 16px;">
            <a href="{{ route('profil.edit') }}" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center; gap: 8px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Perbarui Profil & Password
            </a>
        </div>
    </div>

    {{-- KOLOM KANAN: DETAIL LENGKAP & STATISTIK PENGELOLAAN --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        {{-- KARTU INFORMASI AKUN --}}
        <div class="form-card" style="margin-bottom: 0; padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 14px; margin-bottom: 18px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--teal-light); display: flex; align-items: center; justify-content: center; color: var(--teal-dark);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Informasi Akun Petugas</h3>
                </div>
                <a href="{{ route('profil.edit') }}" style="font-size: 12.5px; color: var(--teal-dark); font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                    Edit Data &rarr;
                </a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; font-size: 13.5px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div style="color: var(--text-secondary); font-size: 11.5px; margin-bottom: 4px; font-weight: 500;">Nama Lengkap</div>
                    <div style="font-weight: 600; color: #0f172a;">{{ $user->name }}</div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div style="color: var(--text-secondary); font-size: 11.5px; margin-bottom: 4px; font-weight: 500;">Username Sistem</div>
                    <div style="font-weight: 600; color: #0f172a;">{{ $user->username }}</div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div style="color: var(--text-secondary); font-size: 11.5px; margin-bottom: 4px; font-weight: 500;">Alamat Email</div>
                    <div style="font-weight: 600; color: #0f172a;">{{ $user->email }}</div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div style="color: var(--text-secondary); font-size: 11.5px; margin-bottom: 4px; font-weight: 500;">Jabatan / Posisi</div>
                    <div style="font-weight: 600; color: #0f172a;">{{ $user->jabatan ?? 'Petugas Perpustakaan' }}</div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div style="color: var(--text-secondary); font-size: 11.5px; margin-bottom: 4px; font-weight: 500;">Peran / Hak Akses</div>
                    <div style="font-weight: 600; color: #0f172a;">{{ ucfirst($user->role ?? 'Pustakawan') }} (Full Access)</div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div style="color: var(--text-secondary); font-size: 11.5px; margin-bottom: 4px; font-weight: 500;">Terakhir Diperbarui</div>
                    <div style="font-weight: 600; color: #0f172a;">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'Baru saja' }}</div>
                </div>
            </div>
        </div>

        {{-- KARTU STATISTIK PENGELOLAAN SISTEM --}}
        <div class="form-card" style="margin-bottom: 0; padding: 24px;">
            <div style="display: flex; align-items: center; gap: 8px; border-bottom: 1px solid var(--border); padding-bottom: 14px; margin-bottom: 18px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #ede9fe; display: flex; align-items: center; justify-content: center; color: #7c3aed;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                </div>
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Ringkasan Data Perpustakaan Terkelola</h3>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 14px;">
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px; text-align: center;">
                    <div style="font-size: 22px; font-weight: 800; color: #15803d; margin-bottom: 2px;">{{ number_format($totalBuku) }}</div>
                    <div style="font-size: 12px; font-weight: 600; color: #166534;">Judul Buku</div>
                </div>

                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 14px; text-align: center;">
                    <div style="font-size: 22px; font-weight: 800; color: #1d4ed8; margin-bottom: 2px;">{{ number_format($totalPeminjam) }}</div>
                    <div style="font-size: 12px; font-weight: 600; color: #1e40af;">Anggota Terdaftar</div>
                </div>

                <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 10px; padding: 14px; text-align: center;">
                    <div style="font-size: 22px; font-weight: 800; color: #a16207; margin-bottom: 2px;">{{ number_format($totalPeminjamanAktif) }}</div>
                    <div style="font-size: 12px; font-weight: 600; color: #854d0e;">Peminjaman Aktif</div>
                </div>

                <div style="background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 10px; padding: 14px; text-align: center;">
                    <div style="font-size: 22px; font-weight: 800; color: #7e22ce; margin-bottom: 2px;">{{ number_format($totalKunjungan) }}</div>
                    <div style="font-size: 12px; font-weight: 600; color: #6b21a8;">Total Kunjungan</div>
                </div>
            </div>
        </div>

        {{-- KARTU KEAMANAN --}}
        <div class="form-card" style="margin-bottom: 0; padding: 22px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef2f2; display: flex; align-items: center; justify-content: center; color: #dc2626; flex-shrink: 0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 700; color: #0f172a;">Keamanan Kata Sandi</div>
                        <div style="font-size: 12.5px; color: var(--text-secondary);">Ganti kata sandi secara berkala untuk menjaga keamanan akun sistem perpustakaan.</div>
                    </div>
                </div>

                <a href="{{ route('profil.edit') }}#keamanan" class="btn btn-outline btn-sm" style="color: #b91c1c; border-color: #fecaca; background: #fff5f5;">
                    Ubah Kata Sandi &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

@endsection