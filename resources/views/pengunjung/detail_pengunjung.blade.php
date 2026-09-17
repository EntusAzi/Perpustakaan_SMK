@extends('layouts.app')

@section('title', 'Detail Pengunjung')
@section('page-title', 'Detail Pengunjung')

@section('content')

<div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Data Pengunjung
    </a>

    <div style="display:flex;gap:8px;">
        <a href="{{ route('pengunjung.create') }}" class="btn btn-primary btn-sm">
            + Catat Kunjungan Baru
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr;gap:20px;max-width:820px;">
    {{-- INFORMASI KUNJUNGAN --}}
    <div class="form-card" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid var(--border);flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:white;flex-shrink:0;">
                    {{ strtoupper(substr($pengunjung->nama, 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">{{ $pengunjung->nama }}</h2>
                    <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                        <span class="badge badge-{{ $pengunjung->tipe }}">{{ ucfirst($pengunjung->tipe) }}</span>
                        @if($member)
                        <span style="font-size:11.5px;font-weight:600;color:#047857;background:#d1fae5;padding:2px 8px;border-radius:12px;">
                            Anggota Terdaftar
                        </span>
                        @else
                        <span style="font-size:11.5px;font-weight:600;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:12px;">
                            Pengunjung Umum
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:8px;align-items:center;">
                <a href="{{ route('pengunjung.edit', $pengunjung) }}" class="btn btn-sm" style="background:#fef3c7;color:#b45309;border:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('pengunjung.destroy', $pengunjung) }}" onsubmit="return confirm('Hapus data kunjungan {{ $pengunjung->nama }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#b91c1c;border:none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:18px;font-size:13.5px;margin-bottom:20px;">
            <div>
                <div style="color:var(--text-secondary);font-size:12px;margin-bottom:3px;">Kelas / Jabatan</div>
                <div style="font-weight:600;color:#1e293b;">{{ $pengunjung->kelas_jabatan ?? '-' }}</div>
            </div>

            <div>
                <div style="color:var(--text-secondary);font-size:12px;margin-bottom:3px;">NIS / NIP</div>
                <div style="font-weight:600;color:#1e293b;">{{ $pengunjung->nis_nip ?? '-' }}</div>
            </div>

            <div>
                <div style="color:var(--text-secondary);font-size:12px;margin-bottom:3px;">Tanggal Kunjungan</div>
                <div style="font-weight:600;color:#1e293b;">{{ $pengunjung->tanggal_kunjungan ? $pengunjung->tanggal_kunjungan->format('d M Y') : '-' }}</div>
            </div>

            <div>
                <div style="color:var(--text-secondary);font-size:12px;margin-bottom:3px;">Waktu Masuk</div>
                <div style="font-weight:600;color:#1e293b;">
                    {{ $pengunjung->waktu_masuk ? \Carbon\Carbon::parse($pengunjung->waktu_masuk)->format('H:i') . ' WIB' : '-' }}
                </div>
            </div>
            
            @if($pengunjung->waktu_keluar)
            <div>
                <div style="color:var(--text-secondary);font-size:12px;margin-bottom:3px;">Waktu Keluar</div>
                <div style="font-weight:600;color:#1e293b;">
                    {{ \Carbon\Carbon::parse($pengunjung->waktu_keluar)->format('H:i') }} WIB
                </div>
            </div>
            @endif

             <div style="font-size:13px;color:var(--text-secondary);">
                <span>No. Telp: <strong style="color:var(--text-primary);">{{ $member->telepon ?? '-' }}</strong></span>
                <span style="margin:0 8px;">&bull;</span>
                <span>Alamat: <strong style="color:var(--text-primary);">{{ $member->alamat ?? '-' }}</strong></span>
            </div>
        </div>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin-bottom:16px;">
            <div style="color:var(--text-secondary);font-size:12px;margin-bottom:4px;font-weight:600;">Keperluan Kunjungan:</div>
            <div style="font-size:14px;color:#334155;line-height:1.5;">
                {{ $pengunjung->keperluan ?? 'Berkunjung & membaca buku di perpustakaan' }}
            </div>
        </div>

        {{-- JIKA TERHUBUNG DENGAN DATA PEMINJAM --}}
        @if($member)
        <div style="border-top:1px solid var(--border);padding-top:16px;margin-top:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <a href="{{ route('peminjam.show', $member) }}" class="btn btn-outline btn-sm">
                Lihat Profil Anggota & Riwayat Peminjaman &rarr;
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
