@extends('layouts.app')

@section('title', 'Daftar Koleksi Buku')
@section('page-title', 'Daftar Koleksi Buku')

@section('content')

{{-- ALERT SUKSES / PESAN --}}
@if(session('success'))
<div style="background:#ccfbf1;border:1px solid #99f6e4;color:#0f766e;padding:12px 16px;border-radius:8px;margin-bottom:16px;display:flex;align-items:center;gap:8px;font-size:13.5px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="toolbar" style="flex-wrap:wrap;gap:12px;">
    <div class="toolbar-left">
        <div style="position:relative;display:inline-flex;align-items:center;">
            <select name="kategori" form="filterForm" class="form-select" onchange="document.getElementById('filterForm').submit()" style="height:37px;font-size:12.5px;padding:4px 30px 4px 12px;font-weight:600;color:#0f766e;border-color:#99f6e4;background:#f0fdfa;border-radius:8px;cursor:pointer;-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:none;">
                <option value="semua" {{ !request('kategori') || request('kategori') === 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="lks_paket" {{ request('kategori') === 'lks_paket' ? 'selected' : '' }}>LKS / Paket</option>
                <option value="referensi" {{ request('kategori') === 'referensi' ? 'selected' : '' }}>Referensi</option>
                <option value="karya_fiksi" {{ request('kategori') === 'karya_fiksi' ? 'selected' : '' }}>Karya Fiksi</option>
                <option value="umum" {{ request('kategori') === 'umum' ? 'selected' : '' }}>Umum</option>
            </select>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;right:10px;pointer-events:none;color:#0f766e;">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </div>
    </div>

    <div class="toolbar-right" style="flex-wrap:wrap;gap:8px;">
        <form id="filterForm" method="GET" action="{{ route('buku.index') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            {{-- Filter Kelas --}}
            <div style="position:relative;display:inline-flex;align-items:center;">
                <select name="kelas" class="form-select" onchange="this.form.submit()" style="height:37px;font-size:12.5px;padding:4px 28px 4px 10px;border:1px solid var(--border);border-radius:8px;background:#ffffff;color:var(--text-primary);cursor:pointer;-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:none;">
                    <option value="">Kelas: Semua</option>
                    <option value="X"     {{ request('kelas') === 'X'     ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI"    {{ request('kelas') === 'XI'    ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII"   {{ request('kelas') === 'XII'   ? 'selected' : '' }}>Kelas XII</option>
                    <option value="Semua" {{ request('kelas') === 'Semua' ? 'selected' : '' }}>Umum / Semua</option>
                </select>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;right:9px;pointer-events:none;color:#6b7280;">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>

            {{-- Filter Status Stok --}}
            <div style="position:relative;display:inline-flex;align-items:center;">
                <select name="status" class="form-select" onchange="this.form.submit()" style="height:37px;font-size:12.5px;padding:4px 28px 4px 10px;border:1px solid var(--border);border-radius:8px;background:#ffffff;color:var(--text-primary);cursor:pointer;-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:none;">
                    <option value="">Status: Semua</option>
                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                </select>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;right:9px;pointer-events:none;color:#6b7280;">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>

            {{-- Input Pencarian Multi-Field --}}
            <div class="input-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Cari judul, pengarang, no inv, ID..." value="{{ request('search') }}" style="min-width:200px;">
            </div>

            @if(request('search') || request('kelas') || (request('kategori') && request('kategori') !== 'semua') || request('status'))
            <a href="{{ route('buku.index') }}" class="btn btn-outline" style="padding:7px 10px;font-size:12px;color:#ef4444;border-color:#fecaca;background:#fef2f2;" title="Reset filter">
                ✕ Reset
            </a>
            @endif
        </form>

        <a href="{{ route('buku.export_import') }}" class="btn btn-outline" style="white-space:nowrap; gap:6px; color:#0f766e; border-color:#99f6e4; background:#f0fdfa;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export / Import Buku
        </a>

        <a href="{{ route('buku.create') }}" class="btn btn-primary" style="white-space:nowrap;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Buku
        </a>
    </div>
</div>

@if($buku->count() > 0)
<div class="book-grid">
    @foreach($buku as $b)
    <div class="book-card" style="display:flex;flex-direction:column;justify-content:space-between;position:relative;">
        <div>
            {{-- Cover Buku --}}
            @if($b->cover_image)
                <img src="{{ asset('storage/' . $b->cover_image) }}" alt="{{ $b->judul }}" class="book-cover">
            @else
                <div class="book-cover-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
            @endif

            <div class="book-info" style="padding-bottom:6px;">
                {{-- Badges: ID Buku & Kelas --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;gap:4px;">
                    <span style="font-size:10.5px;font-weight:700;background:#f1f5f9;color:#0f766e;padding:2px 6px;border-radius:4px;letter-spacing:0.04em;">
                        {{ $b->id_buku ?? 'BK-' . str_pad($b->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @if($b->kelas)
                    <span style="font-size:10.5px;font-weight:700;background:#e0f2fe;color:#0284c7;padding:2px 6px;border-radius:4px;">
                        Kelas {{ $b->kelas }}
                    </span>
                    @endif
                </div>

                {{-- Judul Buku --}}
                <div class="book-title" title="{{ $b->judul }}" style="font-size:14px;line-height:1.35;margin-bottom:4px;">
                    {{ Str::limit($b->judul, 45) }}
                </div>

                {{-- Pengarang & Penerbit --}}
                <div class="book-author" style="font-size:12px;color:#475569;margin-bottom:8px;">
                    {{ $b->penulis }}
                    @if($b->penerbit)
                    <span style="color:#94a3b8;">• {{ Str::limit($b->penerbit, 20) }}</span>
                    @endif
                </div>

                {{-- Metadata Tambahan: Rak & Keterangan --}}
                <div style="font-size:11px;color:#64748b;display:flex;flex-direction:column;gap:3px;margin-bottom:8px;background:#f8fafc;padding:6px 8px;border-radius:6px;border:1px solid #f1f5f9;">
                    @if($b->no_inventaris)
                    <div style="display:flex;justify-content:space-between;">
                        <span>No. Inv:</span>
                        <strong style="color:#334155;">{{ $b->no_inventaris }}</strong>
                    </div>
                    @endif
                    @if($b->nomor_rak)
                    <div style="display:flex;justify-content:space-between;">
                        <span>Lokasi Rak:</span>
                        <strong style="color:#0f766e;">📍 {{ $b->nomor_rak }}</strong>
                    </div>
                    @endif
                    @if($b->keterangan)
                    <div style="display:flex;justify-content:space-between;">
                        <span>Ket / Mapel:</span>
                        <span style="color:#475569;max-width:110px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" title="{{ $b->keterangan }}">{{ $b->keterangan }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer Kartu Buku --}}
        <div class="book-info" style="padding-top:0;">
            <div class="book-footer" style="border-top:1px solid #f1f5f9;padding-top:8px;">
                <div>
                    <span class="badge {{ $b->stok_tersedia > 0 ? 'badge-tersedia' : 'badge-dipinjam' }}" style="font-size:11px;">
                        {{ $b->stok_tersedia > 0 ? 'Tersedia (' . $b->stok_tersedia . '/' . $b->stok . ')' : 'Habis Dipinjam' }}
                    </span>
                </div>

                <div style="display:flex;gap:4px;align-items:center;">
                    {{-- Tombol Detail Modal --}}
                    <button type="button" class="book-menu-btn" title="Lihat 13 Data Detail Buku"
                            onclick="bukaDetailBuku({{ json_encode($b) }})"
                            style="color:#0284c7;background:#f0f9ff;padding:4px 6px;border-radius:4px;display:flex;align-items:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>

                    {{-- Tombol Edit --}}
                    <a href="{{ route('buku.edit', $b) }}" class="book-menu-btn" title="Edit Buku"
                       style="color:#d97706;background:#fffbeb;padding:4px 6px;border-radius:4px;display:flex;align-items:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>

                    {{-- Tombol Hapus --}}
                    <form method="POST" action="{{ route('buku.destroy', $b) }}" onsubmit="return confirm('Hapus buku {{ addslashes($b->judul) }}? Data peminjaman terkait buku ini mungkin terpengaruh.')" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="book-menu-btn" title="Hapus Buku"
                                style="color:#ef4444;background:#fef2f2;padding:4px 6px;border-radius:4px;display:flex;align-items:center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div style="margin-top:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <span style="font-size:12.5px;color:var(--text-secondary);">
        Menampilkan {{ $buku->firstItem() }}-{{ $buku->lastItem() }} dari {{ $buku->total() }} buku
    </span>
    {{ $buku->withQueryString()->links('vendor.pagination.simple') }}
</div>

@else
<div class="card">
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
        </svg>
        <p>Tidak ditemukan koleksi buku dengan filter yang dipilih</p>
        <div style="margin-top:10px;">
            <a href="{{ route('buku.index') }}" class="btn btn-outline btn-sm">Reset Pencarian</a>
            <a href="{{ route('buku.create') }}" class="btn btn-primary btn-sm">+ Tambah Buku Baru</a>
        </div>
    </div>
</div>
@endif

{{-- ======================================================= --}}
{{-- MODAL DETAIL 13 ATRIBUT BUKU LENGKAP                    --}}
{{-- ======================================================= --}}
<div id="modal_detail_buku" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:9999;align-items:center;justify-content:center;padding:16px;">
    <div style="background:#ffffff;border-radius:12px;max-width:680px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 25px -5px rgba(0,0,0,0.2);position:relative;">
        {{-- Header Modal --}}
        <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;background:#f8fafc;border-top-left-radius:12px;border-top-right-radius:12px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:18px;">📖</span>
                <div>
                    <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;" id="m_judul">Judul Buku</h3>
                    <div style="font-size:12px;color:#64748b;" id="m_penulis_sub">Penulis</div>
                </div>
            </div>
            <button type="button" onclick="tutupDetailBuku()" style="background:none;border:none;font-size:20px;color:#94a3b8;cursor:pointer;padding:4px;">✕</button>
        </div>

        {{-- Body Modal: 13 Atribut Lengkap --}}
        <div style="padding:20px;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <tbody>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;width:38%;color:#64748b;font-weight:500;">1. Judul Buku</td>
                        <td style="padding:8px 0;font-weight:700;color:#0f172a;" id="m_t_judul">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">2. Pengarang / Penulis</td>
                        <td style="padding:8px 0;font-weight:600;" id="m_t_pengarang">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">3. Penerbit</td>
                        <td style="padding:8px 0;" id="m_t_penerbit">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">4. Tahun Terbit</td>
                        <td style="padding:8px 0;" id="m_t_tahun">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">5. Jumlah Buku / Stok</td>
                        <td style="padding:8px 0;font-weight:700;color:#0f766e;" id="m_t_jumlah">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">6. Kelas (Tingkat SMK)</td>
                        <td style="padding:8px 0;" id="m_t_kelas"><span class="badge badge-primary">Kelas X</span></td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">7. Kurikulum</td>
                        <td style="padding:8px 0;" id="m_t_kurikulum">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">8. No. Inventaris</td>
                        <td style="padding:8px 0;font-family:monospace;font-weight:600;color:#334155;" id="m_t_inventaris">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">9. Sumber Buku</td>
                        <td style="padding:8px 0;" id="m_t_sumber">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">10. Keterangan (Mapel/Jurusan)</td>
                        <td style="padding:8px 0;" id="m_t_keterangan">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">11. Tanggal Masuk Perpustakaan</td>
                        <td style="padding:8px 0;" id="m_t_tgl_masuk">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">12. Nomor Rak / Lokasi</td>
                        <td style="padding:8px 0;font-weight:700;color:#0d9488;" id="m_t_rak">-</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">13. ID Buku (Kode Sistem)</td>
                        <td style="padding:8px 0;font-weight:700;color:#0284c7;" id="m_t_id_buku">-</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:#64748b;font-weight:500;">Deskripsi / Sinopsis</td>
                        <td style="padding:8px 0;color:#64748b;" id="m_t_deskripsi">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Footer Modal --}}
        <div style="padding:14px 20px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;background:#f8fafc;border-bottom-left-radius:12px;border-bottom-right-radius:12px;">
            <a href="#" id="m_btn_edit" class="btn btn-primary btn-sm">
                ✏️ Edit Buku Ini
            </a>
            <button type="button" onclick="tutupDetailBuku()" class="btn btn-outline btn-sm">Tutup</button>
        </div>
    </div>
</div>

<script>
    function bukaDetailBuku(b) {
        document.getElementById('m_judul').innerText = b.judul || '-';
        document.getElementById('m_penulis_sub').innerText = (b.penulis || '-') + (b.tahun_terbit ? ' (' + b.tahun_terbit + ')' : '');

        document.getElementById('m_t_judul').innerText = b.judul || '-';
        document.getElementById('m_t_pengarang').innerText = b.penulis || '-';
        document.getElementById('m_t_penerbit').innerText = b.penerbit || '-';
        document.getElementById('m_t_tahun').innerText = b.tahun_terbit || '-';
        document.getElementById('m_t_jumlah').innerText = (b.stok || '0') + ' eksemplar (Tersedia: ' + (b.stok_tersedia ?? b.stok) + ')';
        document.getElementById('m_t_kelas').innerText = b.kelas ? ('Kelas ' + b.kelas) : 'Semua / Umum';
        document.getElementById('m_t_kurikulum').innerText = b.kurikulum || '-';
        document.getElementById('m_t_inventaris').innerText = b.no_inventaris || '-';
        document.getElementById('m_t_sumber').innerText = b.sumber || '-';
        document.getElementById('m_t_keterangan').innerText = b.keterangan || '-';
        document.getElementById('m_t_tgl_masuk').innerText = b.tanggal_masuk ? formatTanggalIndo(b.tanggal_masuk) : '-';
        document.getElementById('m_t_rak').innerText = b.nomor_rak || '-';
        document.getElementById('m_t_id_buku').innerText = b.id_buku || ('BK-' + String(b.id).padStart(4, '0'));
        document.getElementById('m_t_deskripsi').innerText = b.deskripsi || '-';

        const btnEdit = document.getElementById('m_btn_edit');
        if (btnEdit) {
            btnEdit.href = '/buku/' + b.id + '/edit';
        }

        const modal = document.getElementById('modal_detail_buku');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function tutupDetailBuku() {
        const modal = document.getElementById('modal_detail_buku');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function formatTanggalIndo(tglStr) {
        try {
            const parts = tglStr.split('T')[0].split('-');
            if (parts.length === 3) {
                const bln = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                return parseInt(parts[2]) + ' ' + bln[parseInt(parts[1]) - 1] + ' ' + parts[0];
            }
        } catch(e) {}
        return tglStr;
    }

    // Tutup modal jika klik di latar belakang gelap
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('modal_detail_buku');
        if (modal && e.target === modal) {
            tutupDetailBuku();
        }
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            tutupDetailBuku();
        }
    });
</script>

@endsection
