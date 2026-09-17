@extends('layouts.app')

@section('title', 'Detail Buku - ' . $buku->judul)
@section('page-title', 'Detail Buku')

@section('content')

<div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <a href="{{ route('buku.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Daftar Buku
    </a>

    <div style="display:flex;gap:8px;">
        <a href="{{ route('buku.edit', $buku) }}" class="btn btn-primary btn-sm">
            ✏️ Edit Buku Ini
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns: 280px 1fr;gap:24px;align-items:start;">
    {{-- Kolom Kiri: Cover & Status Cepat --}}
    <div class="card" style="padding:18px;text-align:center;">
        @if($buku->cover_image)
            <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="{{ $buku->judul }}"
                 style="width:100%;height:320px;object-fit:cover;border-radius:8px;margin-bottom:14px;box-shadow:var(--shadow);">
        @else
            <div style="width:100%;height:260px;background:linear-gradient(135deg, #1a2840, #0f3d5f);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="1.5">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
        @endif

        <div style="font-weight:700;font-size:15px;color:#0f172a;margin-bottom:4px;">
            {{ $buku->id_buku ?? 'BK-' . str_pad($buku->id, 4, '0', STR_PAD_LEFT) }}
        </div>
        <div style="font-size:12px;color:#64748b;margin-bottom:12px;">
            No. Inv: {{ $buku->no_inventaris ?? '-' }}
        </div>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px;text-align:left;font-size:12.5px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <span style="color:#64748b;">Total Stok:</span>
                <strong>{{ $buku->stok }} eksemplar</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <span style="color:#64748b;">Tersedia:</span>
                <strong style="color:#0f766e;">{{ $buku->stok_tersedia }} eksemplar</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <span style="color:#64748b;">Dipinjam:</span>
                <strong style="color:#d97706;">{{ max(0, $buku->stok - $buku->stok_tersedia) }} eksemplar</strong>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#64748b;">Lokasi Rak:</span>
                <strong style="color:#0284c7;">📍 {{ $buku->nomor_rak ?? '-' }}</strong>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: 13 Atribut Buku Lengkap --}}
    <div>
        <div class="card" style="padding:22px;margin-bottom:20px;">
            <h3 style="font-size:16px;font-weight:700;margin-bottom:16px;color:#0f172a;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">
                13 Informasi Atribut Buku
            </h3>

            <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                <tbody>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;width:35%;color:#64748b;font-weight:500;">1. Judul Buku</td>
                        <td style="padding:9px 0;font-weight:700;color:#0f172a;font-size:14.5px;">{{ $buku->judul }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">2. Pengarang / Penulis</td>
                        <td style="padding:9px 0;font-weight:600;">{{ $buku->penulis }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">3. Penerbit</td>
                        <td style="padding:9px 0;">{{ $buku->penerbit ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">4. Tahun Terbit</td>
                        <td style="padding:9px 0;">{{ $buku->tahun_terbit ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">5. Jumlah Buku / Stok</td>
                        <td style="padding:9px 0;font-weight:700;color:#0f766e;">{{ $buku->stok }} eksemplar</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">6. Kelas (Tingkat SMK)</td>
                        <td style="padding:9px 0;">
                            <span class="badge badge-primary">Kelas {{ $buku->kelas ?? 'Semua' }}</span>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">7. Kurikulum</td>
                        <td style="padding:9px 0;">{{ $buku->kurikulum ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">8. No. Inventaris</td>
                        <td style="padding:9px 0;font-family:monospace;font-weight:600;color:#334155;">{{ $buku->no_inventaris ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">9. Sumber Buku</td>
                        <td style="padding:9px 0;">{{ $buku->sumber ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">10. Keterangan (Mapel/Jurusan)</td>
                        <td style="padding:9px 0;font-weight:600;color:#0284c7;">{{ $buku->keterangan ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">11. Tanggal Masuk Perpustakaan</td>
                        <td style="padding:9px 0;">
                            {{ $buku->tanggal_masuk ? $buku->tanggal_masuk->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">12. Nomer Rak</td>
                        <td style="padding:9px 0;font-weight:700;color:#0d9488;">📍 {{ $buku->nomor_rak ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">13. ID Buku (Kode Sistem)</td>
                        <td style="padding:9px 0;font-weight:700;color:#0369a1;">{{ $buku->id_buku ?? 'BK-' . str_pad($buku->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    @if($buku->isbn)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">ISBN</td>
                        <td style="padding:9px 0;font-family:monospace;">{{ $buku->isbn }}</td>
                    </tr>
                    @endif
                    @if($buku->deskripsi)
                    <tr>
                        <td style="padding:9px 0;color:#64748b;font-weight:500;">Deskripsi / Sinopsis</td>
                        <td style="padding:9px 0;color:#475569;line-height:1.5;">{{ $buku->deskripsi }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Riwayat Peminjaman Buku --}}
        <div class="card" style="padding:20px;">
            <h4 style="font-size:15px;font-weight:700;margin-bottom:12px;color:#0f172a;">
                Riwayat Peminjaman Buku Ini
            </h4>

            @if($buku->peminjaman->count() > 0)
            <div class="table-responsive">
                <table class="table" style="font-size:13px;">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buku->peminjaman->take(10) as $pinjam)
                        <tr>
                            <td>
                                <strong>{{ $pinjam->peminjam?->nama ?? '-' }}</strong>
                                <div style="font-size:11.5px;color:#64748b;">{{ $pinjam->peminjam?->kelas_jabatan }}</div>
                            </td>
                            <td>{{ $pinjam->tanggal_pinjam?->format('d/m/Y') }}</td>
                            <td>{{ $pinjam->tanggal_kembali?->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $pinjam->status === 'dikembalikan' ? 'badge-tersedia' : 'badge-dipinjam' }}">
                                    {{ ucfirst($pinjam->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="font-size:13px;color:#64748b;text-align:center;padding:16px 0;">
                Belum ada riwayat peminjaman untuk buku ini.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
