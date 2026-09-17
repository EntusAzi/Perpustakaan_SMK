@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Utama')

@section('content')

{{-- STATS CARDS --}}
<div class="stats-grid">
    {{-- Total Buku --}}
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="label">Total Buku</span>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($totalBuku) }}</div>
        <div class="stat-change {{ $totalBukuChange >= 0 ? 'positive' : 'negative' }}">
            {{ $totalBukuChange >= 0 ? '+' : '' }}{{ $totalBukuChange }}%
            <span class="period">vs bulan lalu</span>
        </div>
    </div>

    {{-- Buku Dipinjam --}}
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="label">Buku Dipinjam</span>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($bukuDipinjam) }}</div>
        <div class="stat-change {{ $bukuDipinjamChange >= 0 ? 'positive' : 'negative' }}">
            {{ $bukuDipinjamChange >= 0 ? '+' : '' }}{{ $bukuDipinjamChange }}%
            <span class="period">vs bulan lalu</span>
        </div>
    </div>

    {{-- Pengunjung Hari Ini --}}
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="label">Pengunjung Hari Ini</span>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($pengunjungHariIni) }}</div>
        <div class="stat-change {{ $pengunjungChange >= 0 ? 'positive' : 'negative' }}">
            {{ $pengunjungChange >= 0 ? '+' : '' }}{{ $pengunjungChange }}%
            <span class="period">vs bulan lalu</span>
        </div>
    </div>

    {{-- Peminjam Aktif --}}
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="label">Peminjam Aktif</span>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($peminjamAktif) }}</div>
        <div class="stat-change negative">
            -3% <span class="period">vs bulan lalu</span>
        </div>
    </div>
</div>

{{-- PEMINJAMAN TERBARU --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2 class="card-title">Peminjaman Terbaru</h2>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body table-container">
        @if($peminjamanTerbaru->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamanTerbaru as $item)
                <tr>
                    <td><span class="name-bold">{{ $item->peminjam->nama ?? '-' }}</span></td>
                    <td>{{ $item->peminjam->kelas_jabatan ?? '-' }}</td>
                    <td>{{ $item->buku->judul ?? '-' }}</td>
                    <td>{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y') : '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $item->status }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            <p>Belum ada data peminjaman</p>
        </div>
        @endif
    </div>
</div>

{{-- PENGUNJUNG TERBARU --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Pengunjung Terbaru</h2>
        <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body table-container">
        @if($pengunjungTerbaru->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Kelas / Jabatan</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Waktu Masuk</th>
                    <th>Keperluan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengunjungTerbaru as $item)
                <tr>
                    <td><span class="name-bold">{{ $item->nama }}</span></td>
                    <td>
                        <span class="badge badge-{{ $item->tipe }}">
                            {{ ucfirst($item->tipe) }}
                        </span>
                    </td>
                    <td>{{ $item->kelas_jabatan ?? '-' }}</td>
                    <td>{{ $item->tanggal_kunjungan ? $item->tanggal_kunjungan->format('d M Y') : '-' }}</td>
                    <td>{{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') . ' WIB' : '-' }}</td>
                    <td>
                        <span style="font-size:13px;color:#334155;">
                            {{ Str::limit($item->keperluan ?? 'Berkunjung ke perpustakaan', 40) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <p>Belum ada data kunjungan terbaru</p>
        </div>
        @endif
    </div>
</div>

@endsection
