@extends('layouts.app')

@section('title', 'Data Peminjaman')
@section('page-title', 'Data Peminjaman')

@section('content')

<div class="toolbar">
    <div class="toolbar-left">
        <div class="filter-tabs">
            <a href="{{ route('peminjaman.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}" class="filter-tab {{ request('status') === 'dipinjam' ? 'active' : '' }}">Dipinjam</a>
            <a href="{{ route('peminjaman.index', ['status' => 'kembali']) }}" class="filter-tab {{ request('status') === 'kembali' ? 'active' : '' }}">Kembali</a>
            <a href="{{ route('peminjaman.index', ['status' => 'terlambat']) }}" class="filter-tab {{ request('status') === 'terlambat' ? 'active' : '' }}">Terlambat</a>
        </div>
    </div>
    <div class="toolbar-right">
        <form method="GET" action="{{ route('peminjaman.index') }}" style="display:flex;gap:8px;align-items:center;">
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            <div class="input-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Cari nama peminjam..." value="{{ request('search') }}">
            </div>
        </form>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Catat Peminjaman
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body table-container">
        @if($peminjaman->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Kelas</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $index => $p)
                <tr>
                    <td>{{ $peminjaman->firstItem() + $index }}</td>
                    <td><span class="name-bold">{{ $p->peminjam->nama ?? '-' }}</span></td>
                    <td>{{ $p->peminjam->kelas_jabatan ?? '-' }}</td>
                    <td>{{ Str::limit($p->buku->judul ?? '-', 35) }}</td>
                    <td>{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td>
                        @if($p->status === 'dipinjam')
                        <form method="POST" action="{{ route('peminjaman.kembalikan', $p) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm" style="background:#d1fae5;color:#065f46;">Kembalikan</button>
                        </form>
                        @else
                        <span style="color:var(--text-light);font-size:12px;">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrap">
            <span class="page-info">Menampilkan {{ $peminjaman->firstItem() }}-{{ $peminjaman->lastItem() }} dari {{ $peminjaman->total() }} data</span>
            {{ $peminjaman->withQueryString()->links('vendor.pagination.simple') }}
        </div>
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

@endsection
