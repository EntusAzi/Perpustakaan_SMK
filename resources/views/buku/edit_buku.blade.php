@extends('layouts.app')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Data Buku')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('buku.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Daftar Buku
    </a>
</div>

<div class="form-card" style="max-width: 900px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;flex-wrap:wrap;gap:8px;">
        <h2 style="font-size:17px;font-weight:700;">Edit Data Buku: {{ $buku->judul }}</h2>
        <!-- <span style="font-size:12px;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;padding:3px 10px;border-radius:12px;font-weight:600;">
            * Semua kolom wajib diisi kecuali Cover & Deskripsi
        </span> -->
    </div>
    <p style="font-size:13px;color:var(--text-secondary);margin-bottom:20px;">
        Perbarui informasi buku, nomor inventaris, penempatan rak, serta klasifikasi kurikulum buku perpustakaan.
    </p>

    @if ($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;">
        <div style="font-weight:700;margin-bottom:4px;">Mohon periksa data yang belum lengkap:</div>
        <ul style="margin:0;padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('buku.update', $buku) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ============================================ --}}
        {{-- BAGIAN 1: ADMINISTRASI & INVENTARIS BUKU     --}}
        {{-- ============================================ --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:14px;">
                1. Administrasi, Inventaris & Lokasi Rak
            </div>

            <div class="form-row">
                {{-- 13. ID Buku --}}
                <div class="form-group">
                    <label class="form-label">ID Buku <span style="color:#ef4444">*</span></label>
                    <input type="text" name="id_buku" class="form-control {{ $errors->has('id_buku') ? 'is-invalid' : '' }}"
                           value="{{ old('id_buku', $buku->id_buku) }}" placeholder="Contoh: BK-0001" required>
                    @error('id_buku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 8. NO INVENTARIS --}}
                <div class="form-group">
                    <label class="form-label">No. Inventaris <span style="color:#ef4444">*</span></label>
                    <input type="text" name="no_inventaris" class="form-control {{ $errors->has('no_inventaris') ? 'is-invalid' : '' }}"
                           value="{{ old('no_inventaris', $buku->no_inventaris) }}" placeholder="Contoh: INV/{{ date('Y') }}/001" required>
                    @error('no_inventaris') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row" style="margin-bottom:0;">
                {{-- 11. Tanggal Masuk Ke Perpustakaan --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tanggal Masuk ke Perpustakaan <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control {{ $errors->has('tanggal_masuk') ? 'is-invalid' : '' }}"
                           value="{{ old('tanggal_masuk', $buku->tanggal_masuk ? $buku->tanggal_masuk->format('Y-m-d') : '') }}" required>
                    @error('tanggal_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 12. Nomer Rak --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Nomor Rak / Lemari <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nomor_rak" class="form-control {{ $errors->has('nomor_rak') ? 'is-invalid' : '' }}"
                           value="{{ old('nomor_rak', $buku->nomor_rak) }}" placeholder="Contoh: Rak A-01 / Lemari 2" required>
                    @error('nomor_rak') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- BAGIAN 2: INFORMASI UTAMA BUKU               --}}
        {{-- ============================================ --}}
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:14px;">
                2. Informasi Utama Buku
            </div>

            {{-- 1. Judul Buku --}}
            <div class="form-group">
                <label class="form-label">Judul Buku <span style="color:#ef4444">*</span></label>
                <input type="text" name="judul" class="form-control {{ $errors->has('judul') ? 'is-invalid' : '' }}"
                       value="{{ old('judul', $buku->judul) }}" placeholder="Masukkan judul buku lengkap" required>
                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                {{-- 2. Pengarang --}}
                <div class="form-group">
                    <label class="form-label">Pengarang / Penulis <span style="color:#ef4444">*</span></label>
                    <input type="text" name="penulis" class="form-control {{ $errors->has('penulis') ? 'is-invalid' : '' }}"
                           value="{{ old('penulis', $buku->penulis) }}" placeholder="Nama pengarang / penulis buku" required>
                    @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 3. Penerbit --}}
                <div class="form-group">
                    <label class="form-label">Penerbit <span style="color:#ef4444">*</span></label>
                    <input type="text" name="penerbit" class="form-control {{ $errors->has('penerbit') ? 'is-invalid' : '' }}"
                           value="{{ old('penerbit', $buku->penerbit) }}" placeholder="Contoh: Erlangga, Kemendikbud, Yudhistira" required>
                    @error('penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row" style="margin-bottom:0;">
                {{-- 4. Tahun Terbit --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tahun Terbit <span style="color:#ef4444">*</span></label>
                    <input type="number" name="tahun_terbit" class="form-control {{ $errors->has('tahun_terbit') ? 'is-invalid' : '' }}"
                           value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                    @error('tahun_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 5. Jumlah Buku --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Jumlah Buku / Total Stok <span style="color:#ef4444">*</span></label>
                    <input type="number" name="stok" class="form-control {{ $errors->has('stok') ? 'is-invalid' : '' }}"
                           value="{{ old('stok', $buku->stok) }}" min="1" required placeholder="Jumlah eksemplar">
                    <small style="font-size:11.5px;color:#64748b;display:block;margin-top:4px;">
                        Saat ini tersedia: <strong>{{ $buku->stok_tersedia }}</strong> eksemplar
                        (Dipinjam: {{ max(0, $buku->stok - $buku->stok_tersedia) }})
                    </small>
                    @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- BAGIAN 3: KLASIFIKASI SEKOLAH & KURIKULUM     --}}
        {{-- ============================================ --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:14px;">
                3. Klasifikasi Sekolah, Kurikulum & Sumber
            </div>

            <div class="form-row">
                {{-- 6. Kelas (x, xi, xii) --}}
                <div class="form-group">
                    <label class="form-label">Peruntukan Kelas <span style="color:#ef4444">*</span></label>
                    <select name="kelas" class="form-control form-select {{ $errors->has('kelas') ? 'is-invalid' : '' }}" required>
                        <option value="" disabled {{ old('kelas', $buku->kelas) ? '' : 'selected' }}>-- Pilih Kelas --</option>
                        <option value="Semua" {{ old('kelas', $buku->kelas) === 'Semua' ? 'selected' : '' }}>Semua Kelas / Umum</option>
                        <option value="X"     {{ old('kelas', $buku->kelas) === 'X'     ? 'selected' : '' }}>Kelas X (Sepuluh)</option>
                        <option value="XI"    {{ old('kelas', $buku->kelas) === 'XI'    ? 'selected' : '' }}>Kelas XI (Sebelas)</option>
                        <option value="XII"   {{ old('kelas', $buku->kelas) === 'XII'   ? 'selected' : '' }}>Kelas XII (Dua Belas)</option>
                    </select>
                    @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 7. Kurikulum --}}
                <div class="form-group">
                    <label class="form-label">Kurikulum <span style="color:#ef4444">*</span></label>
                    <input type="text" name="kurikulum" list="list_kurikulum" class="form-control {{ $errors->has('kurikulum') ? 'is-invalid' : '' }}"
                           value="{{ old('kurikulum', $buku->kurikulum) }}" placeholder="Pilih atau ketik kurikulum..." required>
                    <datalist id="list_kurikulum">
                        <option value="Kurikulum Merdeka">
                        <option value="Kurikulum 2013 (K-13)">
                        <option value="Kurikulum 2013 Revisi">
                        <option value="KTSP">
                        <option value="Umum / Referensi Luar">
                    </datalist>
                    @error('kurikulum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row" style="margin-bottom:0;">
                {{-- 9. Sumber (Hibah, Bos(tahun), dll) --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Sumber Buku <span style="color:#ef4444">*</span></label>
                    <input type="text" name="sumber" list="list_sumber" class="form-control {{ $errors->has('sumber') ? 'is-invalid' : '' }}"
                           value="{{ old('sumber', $buku->sumber) }}" placeholder="Contoh: BOS {{ date('Y') }}, Hibah, dll" required>
                    <datalist id="list_sumber">
                        <option value="BOS {{ date('Y') }}">
                        <option value="BOS {{ date('Y') - 1 }}">
                        <option value="BOS Reguler">
                        <option value="BOS Kinerja">
                        <option value="Hibah / Sumbangan Alumni">
                        <option value="Bantuan Pemerintah / Provinsi">
                        <option value="Dana Komite Sekolah">
                        <option value="Pembelian Mandiri">
                    </datalist>
                    @error('sumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 10. Keterangan (Sejarah, matematika, Rpl, DLL) --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Keterangan (Mata Pelajaran / Keahlian) <span style="color:#ef4444">*</span></label>
                    <input type="text" name="keterangan" list="list_keterangan" class="form-control {{ $errors->has('keterangan') ? 'is-invalid' : '' }}"
                           value="{{ old('keterangan', $buku->keterangan) }}" placeholder="Contoh: RPL, Matematika, Sejarah, TKJ, dll" required>
                    <datalist id="list_keterangan">
                        <option value="Rekayasa Perangkat Lunak (RPL)">
                        <option value="Teknik Komputer & Jaringan (TKJ)">
                        <option value="Matematika">
                        <option value="Bahasa Indonesia">
                        <option value="Bahasa Inggris">
                        <option value="Sejarah Indonesia">
                        <option value="Pendidikan Agama & Budi Pekerti">
                        <option value="Pendidikan Pancasila (PPKn)">
                        <option value="IPAS (Ilmu Pengetahuan Alam & Sosial)">
                        <option value="Informatika">
                        <option value="Produk Kreatif & Kewirausahaan (PKK)">
                        <option value="Buku Fiksi / Novel">
                        <option value="Kamus & Ensiklopedia">
                    </datalist>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- BAGIAN 4: KATEGORI, COVER & DESKRIPSI         --}}
        {{-- ============================================ --}}
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:14px;">
                4. Kategori & Media Pelengkap
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori Buku <span style="color:#ef4444">*</span></label>
                    <select name="kategori" class="form-control form-select {{ $errors->has('kategori') ? 'is-invalid' : '' }}" required>
                        <option value="" disabled {{ old('kategori', $buku->kategori) ? '' : 'selected' }}>-- Pilih Kategori --</option>
                        <option value="lks_paket"   {{ old('kategori', $buku->kategori) === 'lks_paket'   ? 'selected' : '' }}>LKS / Buku Paket</option>
                        <option value="referensi"   {{ old('kategori', $buku->kategori) === 'referensi'   ? 'selected' : '' }}>Referensi</option>
                        <option value="karya_fiksi" {{ old('kategori', $buku->kategori) === 'karya_fiksi' ? 'selected' : '' }}>Karya Fiksi</option>
                        <option value="umum"        {{ old('kategori', $buku->kategori) === 'umum'        ? 'selected' : '' }}>Umum</option>
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ISBN <span style="font-size:11px;color:#64748b;">(Opsional)</span></label>
                    <input type="text" name="isbn" class="form-control {{ $errors->has('isbn') ? 'is-invalid' : '' }}"
                           value="{{ old('isbn', $buku->isbn) }}" placeholder="978-xxx-xxx-xxx-x">
                    @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Cover Buku <span style="font-size:11.5px;color:#64748b;">(Opsional)</span></label>
                @if($buku->cover_image)
                <div style="margin-bottom:10px;display:flex;align-items:center;gap:12px;">
                    <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="Cover" style="height:80px;border-radius:6px;object-fit:cover;border:1px solid #cbd5e1;">
                    <div style="font-size:12px;color:var(--text-secondary);">
                        Cover saat ini.<br>Upload file baru di bawah jika ingin mengganti cover.
                    </div>
                </div>
                @endif
                <input type="file" name="cover_image" class="form-control {{ $errors->has('cover_image') ? 'is-invalid' : '' }}" accept="image/*">
                <div class="form-text">Format: JPG, PNG, WEBP. Maks 2MB. (Dapat dikosongkan jika tidak ada cover).</div>
                @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Deskripsi / Sinopsis Buku <span style="font-size:11.5px;color:#64748b;">(Opsional)</span></label>
                <textarea name="deskripsi" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                          rows="3" placeholder="Deskripsi singkat, sinopsis, atau catatan buku (opsional)...">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- TOMBOL SUBMIT & BATAL --}}
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('buku.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

@endsection
