@extends('layouts.app')

@section('title', 'Buku Tamu & Kunjungan')
@section('page-title', 'Buku Tamu & Kunjungan')

@section('content')

{{-- REKAP HARIAN & AKSI EKSPOR --}}
<div class="visitor-stats" style="flex-wrap:wrap;gap:16px;align-items:center;">
    <div class="visitor-stats-left">
        <div class="vs-date">Rekap Harian Pengunjung</div>
        <div class="vs-title">Hari Ini, {{ \Carbon\Carbon::today()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
    </div>
    
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:12px;">
        <div class="visitor-stats-numbers">
            <div class="vs-num">
                <div class="num">{{ $totalHariIni }}</div>
                <div class="lbl">Total Tamu</div>
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

        {{-- Tombol Export Excel & PDF (Di bawah Total Tamu, Guru, Siswa) --}}
        <div style="display:flex;gap:8px;align-items:center;">
            <a href="{{ route('pengunjung.export.excel', request()->all()) }}" class="btn btn-outline" style="gap:6px;font-size:12px;padding:6px 12px;color:#15803d;border-color:#bbf7d0;background:#f0fdf4;white-space:nowrap;" title="Export data pengunjung ke file Excel (.xls)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('pengunjung.export.pdf', request()->all()) }}" target="_blank" class="btn btn-outline" style="gap:6px;font-size:12px;padding:6px 12px;color:#dc2626;border-color:#fecaca;background:#fef2f2;white-space:nowrap;" title="Cetak atau simpan ke file PDF resmi">
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

{{-- TOOLBAR --}}
<div class="toolbar" style="gap:12px;flex-wrap:wrap;">
    <div class="toolbar-left" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <div class="filter-tabs">
            <a href="{{ route('pengunjung.index', array_merge(request()->except('tipe', 'page'))) }}" 
               class="filter-tab {{ !request('tipe') || request('tipe') === 'semua' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('pengunjung.index', array_merge(request()->except('page'), ['tipe' => 'guru'])) }}" 
               class="filter-tab {{ request('tipe') === 'guru' ? 'active' : '' }}">Guru</a>
            <a href="{{ route('pengunjung.index', array_merge(request()->except('page'), ['tipe' => 'siswa'])) }}" 
               class="filter-tab {{ request('tipe') === 'siswa' ? 'active' : '' }}">Siswa</a>
        </div>

        {{-- Filter Rentang Waktu --}}
        <div class="filter-tabs">
            <a href="{{ route('pengunjung.index', array_merge(request()->except('filter_waktu', 'tanggal', 'page'))) }}" 
               class="filter-tab {{ !request('filter_waktu') && !request('tanggal') ? 'active' : '' }}" title="Hanya pengunjung hari ini">Hari Ini</a>
            <a href="{{ route('pengunjung.index', array_merge(request()->except('tanggal', 'page'), ['filter_waktu' => 'semua'])) }}" 
               class="filter-tab {{ request('filter_waktu') === 'semua' ? 'active' : '' }}" title="Semua data kunjungan lampau">Semua Riwayat</a>
        </div>
    </div>

    <div class="toolbar-right" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <form method="GET" action="{{ route('pengunjung.index') }}" style="display:flex;gap:8px;align-items:center;">
            @if(request('tipe')) <input type="hidden" name="tipe" value="{{ request('tipe') }}"> @endif
            @if(request('filter_waktu')) <input type="hidden" name="filter_waktu" value="{{ request('filter_waktu') }}"> @endif

            <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                   class="form-control" style="font-size:13px;padding:6px 10px;height:38px;" 
                   title="Pilih tanggal kunjungan tertentu" onchange="this.form.submit()">

            <div class="input-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Cari nama pengunjung..." value="{{ request('search') }}">
            </div>
        </form>

        <a href="{{ route('pengunjung.create') }}" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Pengunjung
        </a>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-body table-container">
        @if($pengunjung->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Nama Pengunjung</th>
                    <th>Tipe</th>
                    <th>Kelas / Jabatan</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Waktu Masuk</th>
                    <th>Keperluan</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengunjung as $index => $p)
                <tr>
                    <td>{{ $pengunjung->firstItem() + $index }}</td>
                    <td>
                        <span class="name-bold">{{ $p->nama }}</span>
                    </td>
                    <td><span class="badge badge-{{ $p->tipe }}">{{ ucfirst($p->tipe) }}</span></td>
                    <td>{{ $p->kelas_jabatan ?? '-' }}</td>
                    <td>{{ $p->tanggal_kunjungan ? $p->tanggal_kunjungan->format('d M Y') : '-' }}</td>
                    <td>{{ $p->waktu_masuk ? \Carbon\Carbon::parse($p->waktu_masuk)->format('H:i') . ' WIB' : '-' }}</td>
                    <td>
                        <span style="font-size:13px;color:#334155;">
                            {{ Str::limit($p->keperluan ?? 'Berkunjung ke perpustakaan', 40) }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex;gap:10px;justify-content:flex-end;align-items:center;">
                            <a href="{{ route('pengunjung.show', $p) }}" class="action-link" style="color:var(--teal);font-weight:600;">Detail</a>
                            <a href="{{ route('pengunjung.edit', $p) }}" class="action-link" style="color:#d97706;font-weight:600;">Edit</a>
                            <form method="POST" action="{{ route('pengunjung.destroy', $p) }}" onsubmit="return confirm('Hapus data kunjungan {{ $p->nama }}?')">
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
            <span class="page-info">Menampilkan {{ $pengunjung->firstItem() }}-{{ $pengunjung->lastItem() }} dari {{ $pengunjung->total() }} data</span>
            {{ $pengunjung->withQueryString()->links('vendor.pagination.simple') }}
        </div>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <p>
                @if(request('search') || request('tanggal') || request('tipe'))
                    Tidak ditemukan data pengunjung yang sesuai filter pencarian.
                @elseif(request('filter_waktu') === 'semua')
                    Belum ada riwayat kunjungan perpustakaan.
                @else
                    Belum ada data pengunjung hari ini.
                @endif
            </p>
            <a href="{{ route('pengunjung.create') }}" class="btn btn-primary btn-sm" style="margin-top:8px;">+ Catat Pengunjung</a>
        </div>
        @endif
    </div>
</div>

@endsection
