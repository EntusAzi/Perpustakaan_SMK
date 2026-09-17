@extends('layouts.app')

@section('title', 'Data Peminjam')
@section('page-title', 'Data Peminjam')

@section('content')

{{-- REKAP ANGGOTA PEMINJAM & AKSI EKSPOR --}}
<div class="visitor-stats" style="flex-wrap:wrap;gap:16px;align-items:center;">
    <div class="visitor-stats-left">
        <div class="vs-date">Rekap Data Anggota & Peminjam</div>
        <div class="vs-title">Perpustakaan SMK Negeri 1 Tirtamulya</div>
    </div>
    
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:12px;">
        <div class="visitor-stats-numbers">
            <div class="vs-num">
                <div class="num">{{ $totalPeminjam }}</div>
                <div class="lbl">Total Anggota</div>
            </div>
            <div class="vs-num">
                <div class="num">{{ $totalGuru }}</div>
                <div class="lbl">Guru / Staf</div>
            </div>
            <div class="vs-num">
                <div class="num">{{ $totalSiswa }}</div>
                <div class="lbl">Siswa</div>
            </div>
        </div>

        {{-- Tombol Export Excel & PDF (Di bawah Total Anggota, Guru, Siswa) --}}
        <div style="display:flex;gap:8px;align-items:center;">
            <a href="{{ route('peminjam.export.excel', request()->all()) }}" class="btn btn-outline" style="gap:6px;font-size:12px;padding:6px 12px;color:#15803d;border-color:#bbf7d0;background:#f0fdf4;white-space:nowrap;" title="Export data peminjam ke file Excel (.xls)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('peminjam.export.pdf', request()->all()) }}" target="_blank" class="btn btn-outline" style="gap:6px;font-size:12px;padding:6px 12px;color:#dc2626;border-color:#fecaca;background:#fef2f2;white-space:nowrap;" title="Cetak atau simpan ke file PDF resmi">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Cetak / PDF
            </a>
        </div>
    </div>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <div class="filter-tabs">
            <a href="{{ route('peminjam.index') }}" class="filter-tab {{ !request('tipe') || request('tipe') === 'semua' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('peminjam.index', ['tipe' => 'guru']) }}" class="filter-tab {{ request('tipe') === 'guru' ? 'active' : '' }}">Guru</a>
            <a href="{{ route('peminjam.index', ['tipe' => 'siswa']) }}" class="filter-tab {{ request('tipe') === 'siswa' ? 'active' : '' }}">Siswa</a>
        </div>
    </div>
    <div class="toolbar-right">
        <form method="GET" action="{{ route('peminjam.index') }}" style="display:flex;gap:8px;align-items:center;">
            @if(request('tipe')) <input type="hidden" name="tipe" value="{{ request('tipe') }}"> @endif
            <div class="input-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Cari peminjam..." value="{{ request('search') }}">
            </div>
        </form>
        <a href="{{ route('peminjam.create') }}" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Peminjam
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body table-container">
        @if($peminjam->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Tipe</th>
                    <th>Kelas</th>
                    <th>Buku Dipinjam</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjam as $index => $p)
                @php
                    $aktif = $p->peminjaman()->where('status', 'dipinjam')->count();
                    $total = $p->peminjaman()->count();
                    if ($total === 0) $ket = 'selesai';
                    elseif ($aktif === 0) $ket = 'selesai';
                    elseif ($aktif === $total) $ket = 'belum';
                    else $ket = 'sebagian';
                @endphp
                <tr>
                    <td>{{ $peminjam->firstItem() + $index }}</td>
                    <td><span class="name-bold">{{ $p->nama }}</span></td>
                    <td>
                        <span class="badge badge-{{ $p->tipe }}">{{ ucfirst($p->tipe) }}</span>
                    </td>
                    <td>{{ $p->kelas_jabatan ?? '-' }}</td>
                    <td>{{ $p->buku_dipinjam ?? 0 }} Buku</td>
                    <td>
                        <span class="badge badge-{{ $ket }}">{{ strtoupper($ket) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('peminjam.show', $p) }}" class="action-link">Detail</a>
                            <!-- <a href="{{ route('peminjam.edit', $p) }}" class="action-link" style="color:#6366f1;">Edit</a> -->
                            <form method="POST" action="{{ route('peminjam.destroy', $p) }}" onsubmit="return confirm('Hapus peminjam ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:13px;font-weight:600;padding:0;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrap">
            <span class="page-info">Menampilkan {{ $peminjam->firstItem() }}-{{ $peminjam->lastItem() }} dari {{ $peminjam->total() }} data</span>
            {{ $peminjam->withQueryString()->links('vendor.pagination.simple') }}
        </div>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            <p>Belum ada data peminjam</p>
        </div>
        @endif
    </div>
</div>

@endsection
