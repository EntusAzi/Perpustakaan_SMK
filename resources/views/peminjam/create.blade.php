@extends('layouts.app')

@section('title', 'Layanan Peminjam & Kunjungan')
@section('page-title', 'Form Peminjaman & Kunjungan')

@section('content')

<div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <a href="{{ route('peminjam.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Data Peminjam
    </a>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm">
            Daftar Pengunjung &rarr;
        </a>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">
            Daftar Peminjaman &rarr;
        </a>
    </div>
</div>

<div class="form-card" style="max-width: 780px;">
    <h2 style="font-size:17px;font-weight:700;margin-bottom:6px;">Form Layanan Perpustakaan</h2>
    <p style="font-size:13px;color:var(--text-secondary);margin-bottom:22px;">
        Satu formulir terpadu untuk mencatat identitas siswa/guru, baik yang sekadar berkunjung maupun yang meminjam buku.
    </p>

    {{-- SATU FORM TUNGGAL --}}
    <form method="POST" action="{{ route('peminjam.store') }}">
        @csrf

        {{-- ============================================ --}}
        {{-- BAGIAN 1: IDENTITAS SISWA / GURU            --}}
        {{-- ============================================ --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;display:flex;align-items:center;justify-content:space-between;">
                <span>1. Identitas Siswa / Guru</span>
                <span id="badge-terdaftar" style="font-size:11.5px;font-weight:600;color:#0d9488;background:#ccfbf1;padding:2px 8px;border-radius:12px;display:none;">
                    Data Terdaftar
                </span>
            </div>

            {{-- Dropdown Auto-Fill Anggota Terdaftar --}}
            <div class="form-group" style="position:relative;">
                <label class="form-label" style="font-size:12.5px;color:#475569;">
                    Pilih Anggota Terdaftar (Otomatis Isi) atau Kosongkan untuk Input Baru:
                </label>
                <div style="display:flex;gap:8px;">
                    <select id="peminjam_select" name="peminjam_id" class="form-control form-select" onchange="handlePeminjamSelect(this.value, this)" style="flex:1;">
                        <option value="">-- Ketik Baru / Belum Terdaftar --</option>
                        <option value="cari" style="font-weight:700;color:var(--teal-dark);background:#f0fdfa;">
                            -- Cari Anggota Yang Terdaftar --
                        </option>
                        <!-- <optgroup label="Pilih Langsung dari Daftar:">
                            @foreach($peminjam as $p)
                            <option value="{{ $p->id }}" {{ old('peminjam_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} ({{ $p->kelas_jabatan ?? ucfirst($p->tipe) }}) - [{{ ucfirst($p->tipe) }}]
                            </option>
                            @endforeach
                        </optgroup> -->
                    </select>
                    <button type="button" class="btn btn-outline" onclick="bukaPencarianAnggota()" title="Buka pencarian anggota" style="white-space:nowrap;display:flex;align-items:center;gap:6px;padding:8px 14px;border-color:var(--teal);color:var(--teal-dark);font-weight:600;font-size:13px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        Cari
                    </button>
                </div>

                {{-- Banner Konfirmasi Anggota Dipilih --}}
                <div id="banner_anggota_terpilih" style="display:none;margin-top:10px;background:#f0fdfa;border:1px solid #99f6e4;border-radius:8px;padding:10px 14px;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:#0d9488;color:white;font-size:12px;">✓</span>
                        <div>
                            <span id="banner_nama_anggota" style="font-weight:700;font-size:13px;color:#0f766e;">Nama Anggota</span>
                            <span id="banner_detail_anggota" style="font-size:12px;color:#115e59;margin-left:4px;">(Kelas)</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:6px;">
                        <button type="button" onclick="bukaPencarianAnggota()" class="btn btn-outline btn-sm" style="font-size:11.5px;padding:3px 8px;">
                            🔍 Ganti Anggota
                        </button>
                        <button type="button" onclick="resetKeInputBaru()" class="btn btn-outline btn-sm" style="font-size:11.5px;padding:3px 8px;color:#ef4444;border-color:#fecaca;">
                            ✕ Input Baru
                        </button>
                    </div>
                </div>

                {{-- Kotak Pencarian yang Langsung Muncul Saat Memilih "Cari Anggota Yang Terdaftar" --}}
                <div id="wrapper_cari_anggota" style="display:none;margin-top:12px;background:#ffffff;border:2px solid var(--teal);border-radius:10px;padding:14px;box-shadow:0 10px 25px -5px rgba(13,148,136,0.15);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span style="font-size:13px;font-weight:700;color:var(--teal-dark);">
                                🔍 Pencarian Anggota Terdaftar
                            </span>
                            <span id="badge_total_anggota" style="font-size:11px;background:#e2e8f0;color:#475569;padding:1px 7px;border-radius:10px;">
                                {{ count($peminjam) }} anggota
                            </span>
                        </div>
                        <a href="javascript:void(0)" onclick="tutupPencarian()" style="font-size:12px;color:#ef4444;text-decoration:none;font-weight:600;display:flex;align-items:center;gap:4px;">
                            ✕ Tutup
                        </a>
                    </div>

                    <div style="position:relative;display:flex;align-items:center;margin-bottom:8px;">
                        <span style="position:absolute;left:12px;color:#0d9488;display:flex;align-items:center;pointer-events:none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>

                        <input type="text" id="input_cari_anggota" class="form-control"
                               style="padding-left:38px;padding-right:32px;border:1.5px solid #cbd5e1;background:#f8fafc;font-size:13.5px;"
                               placeholder="Ketik nama, kelas, jabatan, atau NIS/NIP..."
                               autocomplete="off"
                               oninput="filterPencarian(this.value)">

                        <button type="button" id="btn_clear_cari"
                                style="position:absolute;right:10px;background:none;border:none;color:#94a3b8;cursor:pointer;display:none;padding:4px;font-size:13px;"
                                onclick="resetInputCari()" title="Bersihkan">
                            ✕
                        </button>
                    </div>

                    {{-- Hasil Pencarian Inline List --}}
                    <div id="hasil_pencarian_container"
                         style="max-height:220px;overflow-y:auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" id="input_nama" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                           value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Anggota <span style="color:#ef4444">*</span></label>
                    <select id="input_tipe" name="tipe" class="form-control form-select {{ $errors->has('tipe') ? 'is-invalid' : '' }}" required>
                        <option value="" disabled {{ old('tipe') ? '' : 'selected' }}>-- Pilih Tipe --</option>
                        <option value="siswa" {{ old('tipe') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru"  {{ old('tipe') === 'guru'  ? 'selected' : '' }}>Guru</option>
                    </select>
                    @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kelas / Jabatan</label>
                    <input type="text" id="input_kelas" name="kelas_jabatan" class="form-control"
                           value="{{ old('kelas_jabatan') }}" placeholder="Contoh: XII RPL 1 / Guru Matematika">
                </div>

                <div class="form-group">
                    <label class="form-label">NIS / NIP</label>
                    <input type="text" id="input_nis" name="nis_nip" class="form-control"
                           value="{{ old('nis_nip') }}" placeholder="Nomor induk siswa / NIP">
                </div>
            </div>

            <div class="form-row" style="margin-bottom:0;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">No. Telepon / WhatsApp</label>
                    <input type="text" id="input_telepon" name="telepon" class="form-control"
                           value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx (opsional)">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Alamat</label>
                    <input type="text" id="input_alamat" name="alamat" class="form-control"
                           value="{{ old('alamat') }}" placeholder="Alamat tinggal (opsional)">
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- BAGIAN 2: PILIHAN KEPERLUAN & PEMINJAMAN    --}}
        {{-- ============================================ --}}
        @php
            $isPinjamDefault = old('meminjam_buku', '1') == '1';
            $oldBukuIds = old('buku_ids', old('buku_id') ? [old('buku_id')] : []);
            $preselectedBuku = !empty($oldBukuIds) ? $buku->whereIn('id', $oldBukuIds)->values() : collect();
        @endphp

        <div style="background:#ffffff;border:1px solid #cbd5e1;border-radius:10px;padding:18px;margin-bottom:24px;">
            <input type="hidden" name="meminjam_buku" id="meminjam_buku_input" value="1">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">
                    2. Peminjaman Buku Perpustakaan
                </span>
            </div>

            {{-- JIKA MEMINJAM BUKU --}}
            <div id="section_peminjaman_buku" style="{{ $isPinjamDefault ? '' : 'display:none;' }}">
                {{-- PENCARIAN BUKU YANG DIPINJAM (SEARCHING - BUKAN DROPDOWN) --}}
                <div class="form-group" style="position:relative; margin-bottom:18px;">
                    <label class="form-label" style="font-weight:600; display:flex; justify-content:space-between; align-items:center;">
                        <span>Cari & Pilih Buku yang Dipinjam <span style="color:#ef4444">*</span></span>
                        <span id="badge_total_buku_pinjam" style="font-size:11.5px; font-weight:500; color:#0f766e; background:#ccfbf1; padding:2px 8px; border-radius:12px;">
                            {{ $buku->count() }} buku tersedia
                        </span>
                    </label>

                    {{-- Container hidden input buku_ids[] dan fallback buku_id --}}
                    <div id="hidden_peminjam_buku_inputs">
                        <input type="hidden" name="buku_id" id="fallback_buku_id" value="{{ old('buku_id') }}">
                    </div>

                    {{-- Input Pencarian Teks --}}
                    <div style="position:relative;">
                        <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#64748b; display:flex; align-items:center; pointer-events:none;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>
                        <input type="text" id="input_cari_buku_peminjam" class="form-control"
                               style="padding-left:40px; padding-right:32px; height:42px; font-size:13.5px; border:1.5px solid #cbd5e1; border-radius:8px;"
                               placeholder="Ketik judul buku, nama pengarang, ID buku, atau nomor rak..."
                               autocomplete="off"
                               oninput="filterBukuPeminjam(this.value)"
                               onfocus="bukaHasilBukuPeminjam()">

                        <button type="button" id="btn_clear_buku_peminjam"
                                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; display:none; padding:4px; font-size:14px;"
                                onclick="clearCariBukuPeminjam()" title="Bersihkan">✕</button>
                    </div>

                    {{-- Dropdown Container Hasil Pencarian --}}
                    <div id="container_hasil_buku_peminjam"
                         style="display:none; position:relative; margin-top:6px; max-height:220px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); z-index:50;">
                        <div id="list_buku_peminjam_items">
                            @foreach($buku as $b)
                            <div class="item-buku-peminjam"
                                 id="item_buku_peminjam_{{ $b->id }}"
                                 data-id="{{ $b->id }}"
                                 data-judul="{{ strtolower($b->judul) }}"
                                 data-penulis="{{ strtolower($b->penulis ?? '') }}"
                                 data-penerbit="{{ strtolower($b->penerbit ?? '') }}"
                                 data-idbuku="{{ strtolower($b->id_buku ?? '') }}"
                                 data-noinv="{{ strtolower($b->no_inventaris ?? '') }}"
                                 data-rak="{{ strtolower($b->nomor_rak ?? '') }}"
                                 data-kelas="{{ $b->kelas }}"
                                 onclick="tambahBukuPeminjam({{ json_encode($b) }})"
                                 style="padding:10px 14px; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:background 0.15s; display:flex; align-items:center; justify-content:space-between; gap:10px;"
                                 onmouseover="this.style.background='#f0fdfa'"
                                 onmouseout="this.style.background='#ffffff'">
                                <div style="flex:1;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:3px; flex-wrap:wrap;">
                                        <span style="font-weight:700; font-size:13.5px; color:#0f172a;">{{ $b->judul }}</span>
                                        <span style="font-size:10.5px; font-weight:700; background:#f1f5f9; color:#0f766e; padding:1px 6px; border-radius:4px; font-family:monospace;">
                                            {{ $b->id_buku ?? 'BK-' . str_pad($b->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        @if($b->kelas)
                                        <span style="font-size:10.5px; font-weight:600; background:#e0f2fe; color:#0284c7; padding:1px 5px; border-radius:4px;">
                                            Kelas {{ $b->kelas }}
                                        </span>
                                        @endif
                                    </div>
                                    <div style="font-size:12px; color:#64748b; display:flex; gap:10px; flex-wrap:wrap;">
                                        <span>Penulis: <strong>{{ $b->penulis ?: '-' }}</strong></span>
                                        @if($b->nomor_rak)
                                        <span>&bull; Lokasi: <strong style="color:#0f766e;">📍 {{ $b->nomor_rak }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                                    <span class="badge badge-tersedia" style="font-size:11px; padding:3px 8px;">
                                        Stok: {{ $b->stok_tersedia }}
                                    </span>
                                    <span style="font-size:12px; font-weight:600; color:#0f766e; background:#f0fdfa; border:1px solid #99f6e4; padding:3px 8px; border-radius:4px;">
                                        + Tambah
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div id="empty_buku_peminjam_msg" style="display:none; padding:18px; text-align:center; color:#94a3b8; font-size:13px;">
                            Buku tidak ditemukan. Silakan ketik judul atau pengarang lain.
                        </div>
                    </div>

                    {{-- DAFTAR BUKU TERPILIH (BISA LEBIH DARI 1 BUKU) --}}
                    <div style="margin-top:12px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <span style="font-weight:700; font-size:13px; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                <span>📚 Daftar Buku yang Dipinjam</span>
                                <span id="badge_count_peminjam_buku" class="badge badge-primary" style="font-size:11px; padding:2px 8px; background:#0f766e;">0 Buku</span>
                            </span>
                            <span style="font-size:11.5px; color:#64748b;">Klik buku pada hasil pencarian di atas untuk menambah</span>
                        </div>

                        <div id="box_list_peminjam_buku" style="border:1.5px dashed #cbd5e1; border-radius:10px; padding:10px; min-height:75px; max-height:220px; overflow-y:auto; background:#f8fafc;">
                            <div id="empty_peminjam_buku_notice" style="padding:14px; text-align:center; color:#94a3b8; font-size:12px;">
                                Belum ada buku yang dipilih. Silakan cari judul buku di atas dan klik <strong>+ Tambah</strong>.
                            </div>
                            <div id="cards_peminjam_buku_wrapper" style="display:flex; flex-direction:column; gap:8px;"></div>
                        </div>

                        <div id="error_peminjam_buku_required" style="display:none; color:#ef4444; font-size:12px; margin-top:5px; font-weight:600;">
                            ⚠️ Silakan cari dan pilih minimal 1 buku yang akan dipinjam terlebih dahulu.
                        </div>
                        @error('buku_id') <div class="invalid-feedback" style="display:block;">{{ $message }}</div> @enderror
                        @error('buku_ids') <div class="invalid-feedback" style="display:block;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pinjam <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
                        @error('tanggal_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Kembali <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_kembali" class="form-control {{ $errors->has('tanggal_kembali') ? 'is-invalid' : '' }}"
                               value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}">
                        @error('tanggal_kembali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Catatan Peminjaman</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan kondisi buku atau keterangan khusus (opsional)...">{{ old('catatan') }}</textarea>
                </div>
            </div>

            {{-- JIKA HANYA BERKUNJUNG --}}
            <div id="section_hanya_kunjung" style="{{ $isPinjamDefault ? 'display:none;' : '' }}">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Keperluan Berkunjung</label>
                    <input type="text" name="keperluan" list="list_keperluan" class="form-control"
                           value="{{ old('keperluan', 'Membaca buku di tempat') }}" placeholder="Pilih atau ketik keperluan...">
                    <datalist id="list_keperluan">
                        <option value="Membaca buku di tempat">
                        <option value="Mengerjakan tugas / belajar">
                        <option value="Mencari referensi buku">
                        <option value="Bimbingan guru">
                        <option value="Mengembalikan buku">
                    </datalist>
                    <small style="color:var(--text-secondary);display:block;margin-top:4px;">
                        *Anggota ini akan otomatis tercatat di daftar pengunjung dan terdaftar di data peminjam.
                    </small>
                </div>
            </div>
        </div>

        {{-- SATU TOMBOL SUBMIT --}}
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="padding:10px 22px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Simpan Data
            </button>
            <a href="{{ route('peminjam.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

<script>
    const listPeminjam = @json($peminjam);

    function bukaPencarianAnggota() {
        const wrapperCari = document.getElementById('wrapper_cari_anggota');
        const inputCari = document.getElementById('input_cari_anggota');
        if (!wrapperCari) return;

        wrapperCari.style.display = 'block';
        wrapperCari.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        if (inputCari) {
            inputCari.value = '';
            setTimeout(() => {
                inputCari.focus();
                filterPencarian('');
            }, 60);
        }
    }

    function tutupPencarian() {
        const wrapperCari = document.getElementById('wrapper_cari_anggota');
        if (wrapperCari) wrapperCari.style.display = 'none';

        const select = document.getElementById('peminjam_select');
        if (select && (select.value === 'cari' || select.value === '__cari__')) {
            select.value = '';
        }
    }

    function handlePeminjamSelect(val, el) {
        el = el || document.getElementById('peminjam_select');
        const selectedText = el && el.options[el.selectedIndex] ? el.options[el.selectedIndex].text.toLowerCase() : '';

        if (val === 'cari' || val === '__cari__' || selectedText.includes('cari anggota')) {
            bukaPencarianAnggota();
            return;
        }

        if (val) {
            autoFillData(val);
        } else {
            resetKeInputBaru();
        }
    }

    function filterPencarian(query) {
        const container = document.getElementById('hasil_pencarian_container');
        const btnClear = document.getElementById('btn_clear_cari');
        const badgeCount = document.getElementById('badge_total_anggota');
        if (!container) return;

        const q = (query || '').toLowerCase().trim();
        if (btnClear) btnClear.style.display = q ? 'block' : 'none';

        const filtered = listPeminjam.filter(p => {
            const nama = (p.nama || '').toLowerCase();
            const kelas = (p.kelas_jabatan || '').toLowerCase();
            const nis = (p.nis_nip || '').toLowerCase();
            const tipe = (p.tipe || '').toLowerCase();
            return !q || nama.includes(q) || kelas.includes(q) || nis.includes(q) || tipe.includes(q);
        });

        if (badgeCount) {
            badgeCount.innerText = filtered.length + ' anggota';
        }

        if (filtered.length === 0) {
            container.innerHTML = `
                <div style="padding:16px;font-size:13px;color:#64748b;text-align:center;">
                    Tidak ditemukan anggota dengan kata kunci "<strong>${escapeHtml(query)}</strong>".<br>
                    <span style="font-size:12px;color:#0d9488;display:inline-block;margin-top:4px;">
                        Silakan tutup pencarian dan isi formulir secara manual untuk mendaftar baru.
                    </span>
                </div>
            `;
            return;
        }

        let html = '';
        filtered.forEach(p => {
            const isGuru = p.tipe === 'guru';
            const badgeBg = isGuru ? '#ede9fe' : '#ccfbf1';
            const badgeColor = isGuru ? '#6d28d9' : '#0f766e';
            const tipeText = isGuru ? 'Guru' : 'Siswa';
            const initial = (p.nama || '?').substring(0, 1).toUpperCase();
            const subInfo = [p.kelas_jabatan, p.nis_nip ? 'NIS/NIP: ' + p.nis_nip : ''].filter(Boolean).join(' • ');

            html += `
                <div class="peminjam-item-row"
                     style="padding:10px 12px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;cursor:pointer;transition:background 0.12s;"
                     onmouseover="this.style.background='#f8fafc'"
                     onmouseout="this.style.background='white'"
                     onclick="pilihDariPencarian(${p.id})">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:50%;background:${isGuru ? '#f3e8ff' : '#e0f2fe'};color:${isGuru ? '#7c3aed' : '#0284c7'};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;">
                            ${initial}
                        </div>
                        <div>
                            <div style="font-weight:600;color:#1e293b;font-size:13.5px;">${escapeHtml(p.nama)}</div>
                            <div style="font-size:12px;color:#64748b;">${escapeHtml(subInfo || 'Anggota Terdaftar')}</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:12px;background:${badgeBg};color:${badgeColor};">
                            ${tipeText}
                        </span>
                        <button type="button" class="btn btn-outline btn-sm"
                                style="font-size:11.5px;padding:3px 10px;border-color:#0d9488;color:#0d9488;background:#f0fdfa;font-weight:600;"
                                onclick="event.stopPropagation(); pilihDariPencarian(${p.id})">
                            Pilih
                        </button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function pilihDariPencarian(id) {
        const select = document.getElementById('peminjam_select');
        const wrapperCari = document.getElementById('wrapper_cari_anggota');

        if (select) {
            select.value = id;
        }
        if (wrapperCari) {
            wrapperCari.style.display = 'none';
        }

        autoFillData(id);
    }

    function resetInputCari() {
        const inputCari = document.getElementById('input_cari_anggota');
        if (inputCari) {
            inputCari.value = '';
            inputCari.focus();
            filterPencarian('');
        }
    }

    function resetKeInputBaru() {
        const select = document.getElementById('peminjam_select');
        const badge = document.getElementById('badge-terdaftar');
        const banner = document.getElementById('banner_anggota_terpilih');
        const wrapperCari = document.getElementById('wrapper_cari_anggota');

        if (select) select.value = '';
        if (badge) badge.style.display = 'none';
        if (banner) banner.style.display = 'none';
        if (wrapperCari) wrapperCari.style.display = 'none';

        document.getElementById('input_nama').value = '';
        document.getElementById('input_tipe').value = '';
        document.getElementById('input_kelas').value = '';
        document.getElementById('input_nis').value = '';
        document.getElementById('input_telepon').value = '';
        document.getElementById('input_alamat').value = '';
    }

    function autoFillData(id) {
        const select = document.getElementById('peminjam_select');
        const selectedText = select && select.options[select.selectedIndex] ? select.options[select.selectedIndex].text.toLowerCase() : '';

        if (id === 'cari' || id === '__cari__' || selectedText.includes('cari anggota')) {
            bukaPencarianAnggota();
            return;
        }

        const badge = document.getElementById('badge-terdaftar');
        const banner = document.getElementById('banner_anggota_terpilih');
        const wrapperCari = document.getElementById('wrapper_cari_anggota');

        if (!id) {
            resetKeInputBaru();
            return;
        }

        const data = listPeminjam.find(item => item.id == id);
        if (data) {
            document.getElementById('input_nama').value = data.nama || '';
            document.getElementById('input_tipe').value = data.tipe || 'siswa';
            document.getElementById('input_kelas').value = data.kelas_jabatan || '';
            document.getElementById('input_nis').value = data.nis_nip || '';
            document.getElementById('input_telepon').value = data.telepon || '';
            document.getElementById('input_alamat').value = data.alamat || '';

            if (badge) {
                badge.style.display = 'inline-block';
                badge.innerText = 'Anggota ' + (data.tipe === 'guru' ? 'Guru' : 'Siswa') + ' Terdaftar';
            }

            if (banner) {
                const namaEl = document.getElementById('banner_nama_anggota');
                const detailEl = document.getElementById('banner_detail_anggota');
                if (namaEl) namaEl.innerText = data.nama;
                if (detailEl) {
                    const info = [data.kelas_jabatan, data.tipe ? data.tipe.toUpperCase() : ''].filter(Boolean).join(' - ');
                    detailEl.innerText = '(' + info + ')';
                }
                banner.style.display = 'flex';
            }

            if (wrapperCari) {
                wrapperCari.style.display = 'none';
            }
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function toggleMeminjamBuku(isPinjam) {
        const secBuku = document.getElementById('section_peminjaman_buku');
        const secKunjung = document.getElementById('section_hanya_kunjung');

        if (isPinjam) {
            if (secBuku) secBuku.style.display = 'block';
            if (secKunjung) secKunjung.style.display = 'none';
        } else {
            if (secBuku) secBuku.style.display = 'none';
            if (secKunjung) secKunjung.style.display = 'block';
        }
    }

    // ============================================
    // FITUR PENCARIAN BUKU & MULTI-BUKU (TANPA DROPDOWN)
    // ============================================
    let selectedBukuPeminjam = {!! json_encode($preselectedBuku ?? []) !!};

    function filterBukuPeminjam(query) {
        query = (query || '').toLowerCase().trim();
        const container = document.getElementById('container_hasil_buku_peminjam');
        const clearBtn = document.getElementById('btn_clear_buku_peminjam');
        const emptyMsg = document.getElementById('empty_buku_peminjam_msg');
        const items = document.querySelectorAll('.item-buku-peminjam');

        if (clearBtn) clearBtn.style.display = query.length > 0 ? 'block' : 'none';
        if (container) container.style.display = 'block';

        let visibleCount = 0;
        items.forEach(function(el) {
            const judul = el.getAttribute('data-judul') || '';
            const penulis = el.getAttribute('data-penulis') || '';
            const penerbit = el.getAttribute('data-penerbit') || '';
            const idbuku = el.getAttribute('data-idbuku') || '';
            const noinv = el.getAttribute('data-noinv') || '';
            const rak = el.getAttribute('data-rak') || '';
            const kelas = el.getAttribute('data-kelas') || '';

            const match = !query || 
                judul.includes(query) || 
                penulis.includes(query) || 
                penerbit.includes(query) || 
                idbuku.includes(query) || 
                noinv.includes(query) || 
                rak.includes(query) || 
                kelas.toLowerCase().includes(query);

            if (match) {
                el.style.display = 'flex';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });

        if (emptyMsg) {
            emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function bukaHasilBukuPeminjam() {
        const input = document.getElementById('input_cari_buku_peminjam');
        const container = document.getElementById('container_hasil_buku_peminjam');
        if (container) {
            container.style.display = 'block';
            filterBukuPeminjam(input ? input.value : '');
        }
    }

    function clearCariBukuPeminjam() {
        const input = document.getElementById('input_cari_buku_peminjam');
        if (input) {
            input.value = '';
            input.focus();
            filterBukuPeminjam('');
        }
    }

    // Klik di luar container pencarian buku untuk menutup dropdown hasil
    document.addEventListener('click', function(e) {
        const container = document.getElementById('container_hasil_buku_peminjam');
        const input = document.getElementById('input_cari_buku_peminjam');
        const clearBtn = document.getElementById('btn_clear_buku_peminjam');
        if (!container || !input) return;

        if (!container.contains(e.target) && e.target !== input && e.target !== clearBtn) {
            container.style.display = 'none';
        }
    });

    function tambahBukuPeminjam(buku) {
        if (!buku || !buku.id) return;

        const exists = selectedBukuPeminjam.some(b => b.id == buku.id);
        if (exists) {
            alert('Buku "' + buku.judul + '" sudah ada di daftar buku yang dipinjam!');
            return;
        }

        if (buku.stok_tersedia < 1) {
            alert('Maaf, stok buku "' + buku.judul + '" sedang habis.');
            return;
        }

        selectedBukuPeminjam.push(buku);
        renderDaftarBukuPeminjam();

        const errEl = document.getElementById('error_peminjam_buku_required');
        if (errEl) errEl.style.display = 'none';
    }

    function hapusBukuPeminjam(id) {
        selectedBukuPeminjam = selectedBukuPeminjam.filter(b => b.id != id);
        renderDaftarBukuPeminjam();
    }

    function renderDaftarBukuPeminjam() {
        const badgeCount = document.getElementById('badge_count_peminjam_buku');
        const emptyNotice = document.getElementById('empty_peminjam_buku_notice');
        const wrapper = document.getElementById('cards_peminjam_buku_wrapper');
        const hiddenContainer = document.getElementById('hidden_peminjam_buku_inputs');

        if (badgeCount) {
            badgeCount.innerText = selectedBukuPeminjam.length + ' Buku';
        }

        if (hiddenContainer) {
            let hiddenHtml = '';
            selectedBukuPeminjam.forEach(b => {
                hiddenHtml += `<input type="hidden" name="buku_ids[]" value="${b.id}">`;
            });
            const fallbackVal = selectedBukuPeminjam.length > 0 ? selectedBukuPeminjam[0].id : '';
            hiddenHtml += `<input type="hidden" name="buku_id" id="fallback_buku_id" value="${fallbackVal}">`;
            hiddenContainer.innerHTML = hiddenHtml;
        }

        if (!wrapper) return;

        if (selectedBukuPeminjam.length === 0) {
            if (emptyNotice) emptyNotice.style.display = 'block';
            wrapper.innerHTML = '';
            return;
        }

        if (emptyNotice) emptyNotice.style.display = 'none';

        let html = '';
        selectedBukuPeminjam.forEach((b, index) => {
            const idBukuDisplay = b.id_buku || ('BK-' + String(b.id).padStart(4, '0'));
            const penulis = b.penulis || '-';
            const rak = b.nomor_rak ? `<span style="font-size:11px;color:#0f766e;background:#ccfbf1;padding:2px 7px;border-radius:4px;font-weight:600;">📍 Rak: ${escapeHtml(b.nomor_rak)}</span>` : '';
            const kelas = b.kelas ? `<span style="font-size:11px;color:#0284c7;background:#e0f2fe;padding:2px 7px;border-radius:4px;font-weight:600;">Kelas ${escapeHtml(b.kelas)}</span>` : '';

            html += `
                <div style="display:flex;align-items:center;justify-content:space-between;background:#ffffff;border:1.5px solid #0d9488;border-left:5px solid #0d9488;border-radius:8px;padding:10px 14px;box-shadow:0 1px 3px rgba(0,0,0,0.05);gap:12px;">
                    <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                        <div style="width:26px;height:26px;border-radius:50%;background:#0d9488;color:#ffffff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                            ${index + 1}
                        </div>
                        <div style="min-width:0;flex:1;">
                            <div style="font-weight:700;font-size:13.5px;color:#0f172a;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                ${escapeHtml(b.judul)}
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:11.5px;color:#64748b;">
                                <span style="font-family:monospace;font-weight:700;color:#0d9488;background:#f0fdfa;padding:1px 6px;border-radius:4px;">${escapeHtml(idBukuDisplay)}</span>
                                <span>Penulis: <strong>${escapeHtml(penulis)}</strong></span>
                                ${rak}
                                ${kelas}
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="hapusBukuPeminjam(${b.id})"
                            style="background:#fef2f2;border:1px solid #fecaca;color:#ef4444;font-size:12px;font-weight:600;padding:5px 10px;border-radius:6px;cursor:pointer;display:flex;align-items:center;gap:4px;transition:background 0.15s;flex-shrink:0;"
                            onmouseover="this.style.background='#fee2e2'"
                            onmouseout="this.style.background='#fef2f2'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        <span>Hapus</span>
                    </button>
                </div>
            `;
        });
        wrapper.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('peminjam_select');
        if (select && select.value && select.value !== 'cari' && select.value !== '__cari__') {
            autoFillData(select.value);
        }

        renderDaftarBukuPeminjam();

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (select && (select.value === 'cari' || select.value === '__cari__')) {
                    select.value = '';
                }

                const secBuku = document.getElementById('section_peminjaman_buku');
                const isMeminjam = !secBuku || secBuku.style.display !== 'none';

                if (isMeminjam && selectedBukuPeminjam.length === 0) {
                    e.preventDefault();
                    const errEl = document.getElementById('error_peminjam_buku_required');
                    if (errEl) errEl.style.display = 'block';
                    const inputCari = document.getElementById('input_cari_buku_peminjam');
                    if (inputCari) {
                        inputCari.focus();
                        bukaHasilBukuPeminjam();
                    }
                    return false;
                }
            });
        }
    });
</script>

@endsection

