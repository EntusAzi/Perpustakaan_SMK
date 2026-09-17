@extends('layouts.app')

@section('title', 'Export & Import Data Buku')
@section('page-title', 'Export & Import Data Koleksi Buku')

@section('content')

{{-- TOMBOL KEMBALI --}}
<div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
    <a href="{{ route('buku.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Katalog Buku
    </a>

    <div style="display: flex; gap: 8px;">
        <a href="{{ route('buku.create') }}" class="btn btn-outline btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Buku Satuan
        </a>
    </div>
</div>

{{-- ALERT SUKSES / ERROR --}}
@if(session('success'))
<div style="background:#ccfbf1;border:1px solid #99f6e4;color:#0f766e;padding:14px 18px;border-radius:10px;margin-bottom:20px;display:flex;align-items:flex-start;gap:12px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px;">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <div>
        <div style="font-weight:700;font-size:14px;margin-bottom:2px;">Operasi Berhasil!</div>
        <div style="font-size:13.5px;">{{ session('success') }}</div>
    </div>
</div>
@endif

@if(session('error'))
<div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:14px 18px;border-radius:10px;margin-bottom:20px;display:flex;align-items:flex-start;gap:12px;">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px;">
        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    <div>
        <div style="font-weight:700;font-size:14px;margin-bottom:2px;">Peringatan</div>
        <div style="font-size:13.5px;">{{ session('error') }}</div>
    </div>
</div>
@endif

@if(session('import_errors') && count(session('import_errors')) > 0)
<div style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-size:13px;">
    <div style="font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Catatan Import (Beberapa baris dilewati):
    </div>
    <ul style="margin:0;padding-left:20px;line-height:1.6;">
        @foreach(session('import_errors') as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- STATS GRID RINGKASAN KOLEKSI --}}
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px;">
    <div class="stat-card" style="padding: 16px 20px;">
        <div class="stat-card-header">
            <span class="label">Total Judul Buku</span>
            <div class="stat-icon" style="background:#e0f2fe;color:#0284c7;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
        </div>
        <div class="stat-value" style="font-size: 24px;">{{ number_format($totalBuku) }} <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Judul</span></div>
    </div>

    <div class="stat-card" style="padding: 16px 20px;">
        <div class="stat-card-header">
            <span class="label">Total Eksemplar (Fisik)</span>
            <div class="stat-icon" style="background:#ccfbf1;color:#0f766e;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </div>
        </div>
        <div class="stat-value" style="font-size: 24px; color:#0f766e;">{{ number_format($totalEksemplar) }} <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Buku</span></div>
    </div>

    <div class="stat-card" style="padding: 16px 20px;">
        <div class="stat-card-header">
            <span class="label">Stok Siap Dipinjam</span>
            <div class="stat-icon" style="background:#dcfce7;color:#15803d;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <div class="stat-value" style="font-size: 24px; color:#15803d;">{{ number_format($totalTersedia) }} <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Tersedia</span></div>
    </div>

    <div class="stat-card" style="padding: 16px 20px;">
        <div class="stat-card-header">
            <span class="label">Sedang Dipinjam</span>
            <div class="stat-icon" style="background:#fef3c7;color:#b45309;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <div class="stat-value" style="font-size: 24px; color:#b45309;">{{ number_format($totalDipinjam) }} <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Dipinjam</span></div>
    </div>
</div>

{{-- GRID UTAMA: 2 KOLOM (EXPORT KIRI, IMPORT KANAN) --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 24px; margin-bottom: 30px;">

    {{-- ========================================================= --}}
    {{-- KARTU 1: EXPORT DATA BUKU                                 --}}
    {{-- ========================================================= --}}
    <div class="card" style="border: 1px solid #e2e8f0; display:flex; flex-direction:column; justify-content:space-between;">
        <div>
            <div class="card-header" style="background: linear-gradient(to right, #f8fafc, #f1f5f9); border-bottom:1px solid #e2e8f0;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:#0f766e; color:#fff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title" style="margin:0; font-size:16px;">Export Data Buku</h3>
                        <span style="font-size:12px; color:var(--text-secondary);">Unduh seluruh atau sebagian data buku ke Excel & PDF</span>
                    </div>
                </div>
                <span class="badge badge-tersedia">Siap Export</span>
            </div>

            <div style="padding: 24px;">
                <form id="formExport" method="GET" action="{{ route('buku.export.excel') }}" target="_blank">
                    <p style="font-size:13px; color:#475569; margin-bottom:16px;">
                        Pilih kriteria data buku yang ingin diekspor. Anda dapat mengekspor seluruh katalog atau memfilternya per kategori/kelas.
                    </p>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; margin-bottom:16px;">
                        {{-- Filter Kategori --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:12.5px; font-weight:600;">Filter Kategori</label>
                            <select name="kategori" id="export_kategori" class="form-select" style="font-size:13px; padding:8px 10px;">
                                <option value="semua">Semua Kategori ({{ $kategoriCounts['semua'] }})</option>
                                <option value="lks_paket">LKS / Paket ({{ $kategoriCounts['lks_paket'] }})</option>
                                <option value="referensi">Referensi ({{ $kategoriCounts['referensi'] }})</option>
                                <option value="karya_fiksi">Karya Fiksi ({{ $kategoriCounts['karya_fiksi'] }})</option>
                                <option value="umum">Umum ({{ $kategoriCounts['umum'] }})</option>
                            </select>
                        </div>

                        {{-- Filter Kelas --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:12.5px; font-weight:600;">Filter Kelas</label>
                            <select name="kelas" id="export_kelas" class="form-select" style="font-size:13px; padding:8px 10px;">
                                <option value="">Semua Tingkat Kelas</option>
                                <option value="X">Kelas X ({{ $kelasCounts['X'] }})</option>
                                <option value="XI">Kelas XI ({{ $kelasCounts['XI'] }})</option>
                                <option value="XII">Kelas XII ({{ $kelasCounts['XII'] }})</option>
                                <option value="Semua">Umum / Semua ({{ $kelasCounts['Semua'] }})</option>
                            </select>
                        </div>
                    </div>

                    {{-- Filter Status Stok --}}
                    <div class="form-group" style="margin-bottom:20px;">
                        <label class="form-label" style="font-size:12.5px; font-weight:600;">Status Ketersediaan</label>
                        <select name="status" id="export_status" class="form-select" style="font-size:13px; padding:8px 10px;">
                            <option value="">Semua Status Stok</option>
                            <option value="tersedia">Hanya Buku Tersedia</option>
                            <option value="dipinjam">Hanya Buku yang Sedang Dipinjam</option>
                        </select>
                    </div>

                    {{-- Informasi Kolom yang di-export --}}
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:24px; font-size:12px; color:#64748b;">
                        <div style="font-weight:600; color:#334155; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            Mencakup 13 Atribut Lengkap SMK:
                        </div>
                        ID Buku, No Inventaris, Judul, Pengarang, Penerbit, Tahun Terbit, Jumlah/Stok, Kelas, Kurikulum, Sumber, Keterangan (Mapel), Tanggal Masuk, Nomor Rak, Kategori, & ISBN.
                    </div>

                    {{-- Tombol Aksi Export --}}
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <div style="display:flex; gap:10px;">
                            {{-- Export Excel (.xls) --}}
                            <button type="submit" onclick="submitExport('{{ route('buku.export.excel') }}?format=xls')" class="btn btn-primary" style="flex:1; justify-content:center; padding:11px; background:#107c41; border-color:#107c41;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/>
                                </svg>
                                Export ke Excel (.xls)
                            </button>

                            {{-- Export CSV --}}
                            <button type="submit" onclick="submitExport('{{ route('buku.export.excel') }}?format=csv')" class="btn btn-outline" style="justify-content:center; padding:11px 16px; font-size:13px;" title="Export file CSV UTF-8">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                </svg>
                                CSV
                            </button>
                        </div>

                        {{-- Cetak / Export PDF --}}
                        <button type="button" onclick="submitExport('{{ route('buku.export.pdf') }}')" class="btn btn-outline" style="width:100%; justify-content:center; padding:11px; color:#b91c1c; border-color:#fca5a5; background:#fff5f5;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            Cetak / Simpan sebagai PDF (A4 Landscape)
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div style="background:#f8fafc; padding:12px 24px; border-top:1px solid #e2e8f0; font-size:12px; color:#64748b;">
            💡 <strong>Tips PDF:</strong> File PDF dilengkapi Kop Resmi Sekolah, tabel rapi lanskap, dan lembar tanda tangan Kepala Sekolah & Petugas Perpustakaan.
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- KARTU 2: IMPORT BUKU MASSAL DARI EXCEL                   --}}
    {{-- ========================================================= --}}
    <div class="card" style="border: 1px solid #e2e8f0; display:flex; flex-direction:column; justify-content:space-between;">
        <div>
            <div class="card-header" style="background: linear-gradient(to right, #f8fafc, #f1f5f9); border-bottom:1px solid #e2e8f0;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:#0284c7; color:#fff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title" style="margin:0; font-size:16px;">Import Data Buku Massal</h3>
                        <span style="font-size:12px; color:var(--text-secondary);">Masukkan puluhan atau ratusan buku sekaligus dari Excel</span>
                    </div>
                </div>
                <span class="badge badge-lks">Excel / CSV</span>
            </div>

            <div style="padding: 24px;">
                {{-- Unduh Template Section --}}
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:14px 16px; margin-bottom:20px;">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                        <div>
                            <div style="font-weight:700; color:#1e40af; font-size:13.5px; margin-bottom:4px;">
                                📥 Belum Punya Format Template?
                            </div>
                            <div style="font-size:12px; color:#3b82f6; line-height:1.4;">
                                Unduh template resmi kami dengan susunan 13 kolom yang sudah disesuaikan dengan kurikulum dan administrasi perpustakaan SMK.
                            </div>
                        </div>
                        <div style="display:flex; gap:6px; margin-top:4px;">
                            <a href="{{ route('buku.template.download', ['format' => 'xls']) }}" class="btn btn-sm" style="background:#1e40af; color:#fff; text-decoration:none; padding:6px 10px; font-size:12px;">
                                ⬇ Template Excel (.xls)
                            </a>
                            <a href="{{ route('buku.template.download', ['format' => 'csv']) }}" class="btn btn-outline btn-sm" style="background:#fff; font-size:12px; padding:6px 10px;">
                                ⬇ CSV
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Form Upload Import --}}
                <form method="POST" action="{{ route('buku.import.excel') }}" enctype="multipart/form-data" id="formImport">
                    @csrf

                    {{-- Pilihan Penanganan Duplikat --}}
                    <div class="form-group" style="margin-bottom:16px;">
                        <label class="form-label" style="font-size:12.5px; font-weight:600;">Jika ID Buku / No. Inventaris Sudah Ada:</label>
                        <div style="display:flex; gap:16px; margin-top:6px;">
                            <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer; color:#334155;">
                                <input type="radio" name="duplicate_mode" value="skip" checked style="accent-color:#0f766e;">
                                <span>Lewati data (Aman)</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer; color:#334155;">
                                <input type="radio" name="duplicate_mode" value="update" style="accent-color:#0f766e;">
                                <span>Perbarui data lama</span>
                            </label>
                        </div>
                    </div>

                    {{-- Dropzone / Upload Box --}}
                    <div class="form-group" style="margin-bottom:20px;">
                        <label class="form-label" style="font-size:12.5px; font-weight:600;">Pilih File Excel / CSV <span style="color:#ef4444">*</span></label>
                        <div id="dropZone" style="border:2px dashed #cbd5e1; border-radius:10px; padding:24px 16px; text-align:center; background:#f8fafc; cursor:pointer; transition:all 0.2s;">
                            <input type="file" name="file_excel" id="file_excel" accept=".xlsx, .xls, .csv, .txt" style="display:none;" required onchange="tampilkanNamaFile(this)">
                            <div id="dropZonePrompt">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.8" style="margin-bottom:8px;">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <div style="font-weight:600; font-size:13.5px; color:#334155; margin-bottom:2px;">
                                    Klik untuk memilih berkas atau geser ke sini
                                </div>
                                <div style="font-size:11.5px; color:#94a3b8;">
                                    Mendukung format: <strong>.xlsx</strong>, <strong>.xls</strong>, atau <strong>.csv</strong> (Maksimal 10 MB)
                                </div>
                            </div>
                            <div id="fileInfoBox" style="display:none; align-items:center; justify-content:center; gap:10px; background:#fff; border:1px solid #99f6e4; padding:10px 14px; border-radius:8px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                </svg>
                                <div style="text-align:left;">
                                    <div id="fileNameTxt" style="font-weight:700; font-size:13px; color:#0f766e;">nama_file.xlsx</div>
                                    <div id="fileSizeTxt" style="font-size:11px; color:#64748b;">12.4 KB</div>
                                </div>
                                <button type="button" onclick="hapusPilihanFile(event)" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:16px; margin-left:8px;" title="Ganti File">✕</button>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Mulai Import --}}
                    <button type="submit" id="btnSubmitImport" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px; font-size:14px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                        </svg>
                        Mulai Import Data Buku Sekarang
                    </button>
                </form>
            </div>
        </div>

        <div style="background:#f8fafc; padding:12px 24px; border-top:1px solid #e2e8f0; font-size:12px; color:#64748b;">
            💡 <strong>Info Sistem:</strong> Jika kolom <em>ID Buku</em> kosong pada spreadsheet Anda, sistem akan secara otomatis membuatkan kode urut resmi (contoh: <code>BK-0011</code>).
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- KARTU 3: PANDUAN STRUKTUR 13 ATRIBUT KOLOM EXCEL          --}}
{{-- ========================================================= --}}
<div class="card" style="border:1px solid #e2e8f0; margin-bottom:30px;">
    <div class="card-header" style="background:#f8fafc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:18px;">📋</span>
            <h3 class="card-title" style="margin:0; font-size:15px;">Tabel Panduan Susunan Kolom File Excel (13 Atribut SMK)</h3>
        </div>
        <span style="font-size:12px; color:#64748b;">Pastikan baris pertama berisi nama kolom seperti tabel berikut</span>
    </div>

    <div class="table-container">
        <table class="data-table" style="font-size:12.5px;">
            <thead>
                <tr>
                    <th style="width:40px; text-align:center;">No</th>
                    <th>Nama Kolom Header</th>
                    <th>Atribut Database</th>
                    <th>Status</th>
                    <th>Tipe Data</th>
                    <th>Contoh Isian yang Benar</th>
                    <th>Keterangan / Aturan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:center; font-weight:700;">1</td>
                    <td style="font-weight:700; color:#0f766e;">ID Buku</td>
                    <td><code>id_buku</code></td>
                    <td><span class="badge badge-umum">Opsional</span></td>
                    <td>Teks</td>
                    <td><code>BK-0001</code></td>
                    <td>Jika dikosongkan, sistem membuatkan nomor urut otomatis.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">2</td>
                    <td style="font-weight:700; color:#0f766e;">No Inventaris</td>
                    <td><code>no_inventaris</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td><code>INV/2026/001</code></td>
                    <td>Nomor registrasi inventaris aset perpustakaan sekolah.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">3</td>
                    <td style="font-weight:700; color:#0f766e;">Judul Buku</td>
                    <td><code>judul</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td>Matematika Tingkat Lanjut Kelas XI</td>
                    <td>Judul buku lengkap sesuai cover.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">4</td>
                    <td style="font-weight:700; color:#0f766e;">Pengarang</td>
                    <td><code>penulis</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td>Budi Raharjo, M.Pd.</td>
                    <td>Nama penulis / pengarang buku.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">5</td>
                    <td style="font-weight:700; color:#0f766e;">Penerbit</td>
                    <td><code>penerbit</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td>Erlangga / Kemendikbudristek</td>
                    <td>Nama penerbit atau percetakan.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">6</td>
                    <td style="font-weight:700; color:#0f766e;">Tahun Terbit</td>
                    <td><code>tahun_terbit</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Angka (YYYY)</td>
                    <td><code>2023</code></td>
                    <td>Tahun penerbitan cetakan buku.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">7</td>
                    <td style="font-weight:700; color:#0f766e;">Jumlah Buku</td>
                    <td><code>stok</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Angka</td>
                    <td><code>30</code></td>
                    <td>Jumlah fisik eksemplar buku yang dimiliki.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">8</td>
                    <td style="font-weight:700; color:#0f766e;">Kelas</td>
                    <td><code>kelas</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td><code>X</code>, <code>XI</code>, <code>XII</code>, atau <code>Semua</code></td>
                    <td>Tingkatan kelas peruntukan siswa SMK.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">9</td>
                    <td style="font-weight:700; color:#0f766e;">Kurikulum</td>
                    <td><code>kurikulum</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td>Kurikulum Merdeka / K-13 Revisi</td>
                    <td>Dasar kurikulum acuan buku.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">10</td>
                    <td style="font-weight:700; color:#0f766e;">Sumber Buku</td>
                    <td><code>sumber</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td>BOS Reguler 2024 / Hibah Alumni</td>
                    <td>Asal perolehan pengadaan buku.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">11</td>
                    <td style="font-weight:700; color:#0f766e;">Keterangan Mapel</td>
                    <td><code>keterangan</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td>Rekayasa Perangkat Lunak / Matematika</td>
                    <td>Mata pelajaran atau program keahlian.</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">12</td>
                    <td style="font-weight:700; color:#0f766e;">Tanggal Masuk</td>
                    <td><code>tanggal_masuk</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Tanggal (YYYY-MM-DD)</td>
                    <td><code>2024-01-15</code></td>
                    <td>Waktu buku dicatat ke perpustakaan (default: hari ini).</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:700;">13</td>
                    <td style="font-weight:700; color:#0f766e;">Nomor Rak</td>
                    <td><code>nomor_rak</code></td>
                    <td><span class="badge badge-terlambat" style="background:#fef2f2;color:#b91c1c;">Wajib</span></td>
                    <td>Teks</td>
                    <td><code>RAK-B2</code></td>
                    <td>Posisi letak rak / lemari buku di perpustakaan.</td>
                </tr>
                <tr style="background:#fbfcfe;">
                    <td style="text-align:center; font-weight:700;">+</td>
                    <td style="font-weight:600; color:#475569;">Kategori</td>
                    <td><code>kategori</code></td>
                    <td><span class="badge badge-umum">Opsional</span></td>
                    <td>Pilihan</td>
                    <td><code>lks_paket</code>, <code>referensi</code>, <code>karya_fiksi</code>, <code>umum</code></td>
                    <td>Klasifikasi tab kategori buku (default: umum).</td>
                </tr>
                <tr style="background:#fbfcfe;">
                    <td style="text-align:center; font-weight:700;">+</td>
                    <td style="font-weight:600; color:#475569;">ISBN</td>
                    <td><code>isbn</code></td>
                    <td><span class="badge badge-umum">Opsional</span></td>
                    <td>Teks</td>
                    <td><code>978-602-01-1234-5</code></td>
                    <td>Standar nomor ISBN jika tersedia pada buku.</td>
                </tr>
                <tr style="background:#fbfcfe;">
                    <td style="text-align:center; font-weight:700;">+</td>
                    <td style="font-weight:600; color:#475569;">Deskripsi / Sinopsis</td>
                    <td><code>deskripsi</code></td>
                    <td><span class="badge badge-umum">Opsional</span></td>
                    <td>Teks Panjang</td>
                    <td>Ringkasan sinopsis atau catatan buku</td>
                    <td>Keterangan ringkas isi buku untuk rujukan siswa.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function submitExport(baseUrl) {
        const form = document.getElementById('formExport');
        const kat = document.getElementById('export_kategori').value;
        const kls = document.getElementById('export_kelas').value;
        const st  = document.getElementById('export_status').value;

        let url = baseUrl;
        url += (url.includes('?') ? '&' : '?') + 'kategori=' + encodeURIComponent(kat);
        if (kls) url += '&kelas=' + encodeURIComponent(kls);
        if (st)  url += '&status=' + encodeURIComponent(st);

        window.open(url, '_blank');
    }

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file_excel');
    const promptBox = document.getElementById('dropZonePrompt');
    const infoBox = document.getElementById('fileInfoBox');
    const nameTxt = document.getElementById('fileNameTxt');
    const sizeTxt = document.getElementById('fileSizeTxt');

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#0f766e';
            dropZone.style.background = '#f0fdfa';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#cbd5e1';
            dropZone.style.background = '#f8fafc';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#cbd5e1';
            dropZone.style.background = '#f8fafc';
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                tampilkanNamaFile(fileInput);
            }
        });
    }

    function tampilkanNamaFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            nameTxt.innerText = file.name;
            sizeTxt.innerText = formatBytes(file.size);
            promptBox.style.display = 'none';
            infoBox.style.display = 'flex';
        }
    }

    function hapusPilihanFile(e) {
        e.stopPropagation();
        fileInput.value = '';
        promptBox.style.display = 'block';
        infoBox.style.display = 'none';
    }

    function formatBytes(bytes, decimals = 1) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
</script>

@endsection
