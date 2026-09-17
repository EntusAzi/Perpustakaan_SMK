@extends('layouts.app')

@section('title', 'Tambah Pengunjung')
@section('page-title', 'Tambah Pengunjung')

@section('content')

<div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Data Pengunjung
    </a>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('peminjam.index') }}" class="btn btn-outline btn-sm">
            Data Anggota & Peminjam &rarr;
        </a>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">
            Daftar Peminjaman &rarr;
        </a>
    </div>
</div>

<div class="form-card" style="max-width: 780px;">
    <h2 style="font-size:17px;font-weight:700;margin-bottom:6px;">Form Kunjungan & Layanan Perpustakaan</h2>
    <p style="font-size:13px;color:var(--text-secondary);margin-bottom:22px;">
        Satu formulir terpadu untuk mencatat buku tamu pengunjung sekaligus sinkronisasi data anggota perpustakaan atau peminjaman buku.
    </p>

    {{-- SATU FORM TUNGGAL --}}
    <form method="POST" action="{{ route('pengunjung.store') }}">
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
                    <input type="text" id="input_nama" name="nama" class="form-control {{ $errors?->has('nama') ? 'is-invalid' : '' }}"
                           value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Anggota <span style="color:#ef4444">*</span></label>
                    <select id="input_tipe" name="tipe" class="form-control form-select {{ $errors?->has('tipe') ? 'is-invalid' : '' }}" required>
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
            $isPinjamDefault = old('meminjam_buku', '0') == '1';
        @endphp

        <div style="background:#ffffff;border:1px solid #cbd5e1;border-radius:10px;padding:18px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);">
                    2. Aktivitas di Perpustakaan Hari Ini
                </span>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:600;font-size:13px;color:var(--teal-dark);">
                    <input type="checkbox" name="meminjam_buku" id="checkbox_meminjam" value="1" 
                           {{ $isPinjamDefault ? 'checked' : '' }} onchange="toggleMeminjamBuku(this.checked)"
                           style="width:16px;height:16px;accent-color:var(--teal);cursor:pointer;">
                    <span>Centang jika sekalian meminjam buku</span>
                </label>
            </div>

            {{-- JIKA HANYA BERKUNJUNG --}}
            <div id="section_hanya_kunjung" style="{{ $isPinjamDefault ? 'display:none;' : '' }}">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Kunjungan <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_kunjungan" class="form-control {{ $errors?->has('tanggal_kunjungan') ? 'is-invalid' : '' }}"
                               value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}">
                        @error('tanggal_kunjungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Waktu Masuk <span style="color:#ef4444">*</span></label>
                        <input type="time" name="waktu_masuk" class="form-control {{ $errors?->has('waktu_masuk') ? 'is-invalid' : '' }}"
                               value="{{ old('waktu_masuk', date('H:i')) }}">
                        @error('waktu_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Keperluan Berkunjung</label>
                    <input type="text" name="keperluan" list="list_keperluan" class="form-control"
                           value="{{ old('keperluan', 'Membaca buku di tempat') }}" placeholder="Pilih atau ketik keperluan...">
                    <datalist id="list_keperluan">
                        <option value="Membaca buku di tempat">
                        <option value="Mengerjakan tugas / belajar kelompok">
                        <option value="Mencari referensi materi / modul">
                        <option value="Bimbingan & diskusi dengan guru">
                        <option value="Mengembalikan buku pinjaman">
                    </datalist>
                    <small style="color:var(--text-secondary);display:block;margin-top:4px;">
                        *Data pengunjung akan langsung tercatat di buku tamu dan otomatis terdaftar sebagai anggota perpustakaan.
                    </small>
                </div>
            </div>

            {{-- JIKA MEMINJAM BUKU --}}
            <div id="section_peminjaman_buku" style="{{ $isPinjamDefault ? '' : 'display:none;' }}">
                <div class="form-group">
                    <label class="form-label">Judul Buku yang Dipinjam <span style="color:#ef4444">*</span></label>
                    <select name="buku_id" id="buku_id" class="form-control form-select {{ $errors?->has('buku_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Buku yang Tersedia --</option>
                        @foreach($buku as $b)
                        <option value="{{ $b->id }}" {{ old('buku_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->judul }} &bull; {{ $b->penulis }} (Tersedia: {{ $b->stok_tersedia }})
                        </option>
                        @endforeach
                    </select>
                    @error('buku_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pinjam <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control {{ $errors?->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
                        @error('tanggal_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Kembali <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_kembali" class="form-control {{ $errors?->has('tanggal_kembali') ? 'is-invalid' : '' }}"
                               value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}">
                        @error('tanggal_kembali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Catatan Peminjaman</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan kondisi buku atau keterangan khusus (opsional)...">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- TOMBOL SUBMIT & BATAL --}}
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="padding:10px 22px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Simpan Data Pengunjung
            </button>
            <a href="{{ route('pengunjung.index') }}" class="btn btn-outline">Batal</a>
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
            document.getElementById('input_tipe').value = data.tipe || '';
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
            secBuku.style.display = 'block';
            secKunjung.style.display = 'none';
        } else {
            secBuku.style.display = 'none';
            secKunjung.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('peminjam_select');
        if (select && select.value && select.value !== 'cari' && select.value !== '__cari__') {
            autoFillData(select.value);
        }

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (select && (select.value === 'cari' || select.value === '__cari__')) {
                    select.value = '';
                }
            });
        }
    });
</script>

@endsection
