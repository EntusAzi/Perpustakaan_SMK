@extends('layouts.app')

@section('title', 'Edit Pengunjung')
@section('page-title', 'Edit Data Pengunjung')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Data Pengunjung
    </a>
</div>

<div class="form-card" style="max-width: 780px;">
    <h2 style="font-size:16px;font-weight:700;margin-bottom:6px;">Edit Data Kunjungan Pengunjung</h2>
    <p style="font-size:13px;color:var(--text-secondary);margin-bottom:20px;">
        Perbarui identitas pengunjung, tanggal kunjungan, waktu kehadiran, atau keperluan.
    </p>

    <form method="POST" action="{{ route('pengunjung.update', $pengunjung) }}">
        @csrf @method('PUT')

        {{-- BAGIAN IDENTITAS --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:20px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">
                1. Identitas Pengunjung
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama" class="form-control {{ $errors?->has('nama') ? 'is-invalid' : '' }}"
                           value="{{ old('nama', $pengunjung->nama) }}" placeholder="Masukkan nama lengkap" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Anggota <span style="color:#ef4444">*</span></label>
                    <select name="tipe" class="form-control form-select {{ $errors?->has('tipe') ? 'is-invalid' : '' }}" required>
                        <option value="siswa" {{ old('tipe', $pengunjung->tipe) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru"  {{ old('tipe', $pengunjung->tipe) === 'guru'  ? 'selected' : '' }}>Guru</option>
                    </select>
                    @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row" style="margin-bottom:0;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Kelas / Jabatan</label>
                    <input type="text" name="kelas_jabatan" class="form-control"
                           value="{{ old('kelas_jabatan', $pengunjung->kelas_jabatan) }}" placeholder="Contoh: XII RPL 1 / Guru Matematika">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">NIS / NIP</label>
                    <input type="text" name="nis_nip" class="form-control"
                           value="{{ old('nis_nip', $pengunjung->nis_nip) }}" placeholder="Nomor induk">
                </div>
            </div>
        </div>

        {{-- BAGIAN DATA KUNJUNGAN --}}
        <div style="background:#ffffff;border:1px solid #cbd5e1;border-radius:10px;padding:18px;margin-bottom:24px;">
            <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:14px;">
                2. Detail Kehadiran & Keperluan
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Kunjungan <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_kunjungan" class="form-control {{ $errors?->has('tanggal_kunjungan') ? 'is-invalid' : '' }}"
                           value="{{ old('tanggal_kunjungan', $pengunjung->tanggal_kunjungan ? $pengunjung->tanggal_kunjungan->format('Y-m-d') : date('Y-m-d')) }}" required>
                    @error('tanggal_kunjungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu Masuk <span style="color:#ef4444">*</span></label>
                    <input type="time" name="waktu_masuk" class="form-control {{ $errors?->has('waktu_masuk') ? 'is-invalid' : '' }}"
                           value="{{ old('waktu_masuk', $pengunjung->waktu_masuk ? substr($pengunjung->waktu_masuk, 0, 5) : date('H:i')) }}" required>
                    @error('waktu_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu Keluar (Opsional)</label>
                    <input type="time" name="waktu_keluar" class="form-control"
                           value="{{ old('waktu_keluar', $pengunjung->waktu_keluar ? substr($pengunjung->waktu_keluar, 0, 5) : '') }}">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Keperluan Berkunjung</label>
                <input type="text" name="keperluan" list="list_keperluan" class="form-control"
                       value="{{ old('keperluan', $pengunjung->keperluan) }}" placeholder="Pilih atau ketik keperluan...">
                <datalist id="list_keperluan">
                    <option value="Membaca buku di tempat">
                    <option value="Mengerjakan tugas / belajar kelompok">
                    <option value="Mencari referensi materi / modul">
                    <option value="Bimbingan & diskusi dengan guru">
                    <option value="Mengembalikan buku pinjaman">
                </datalist>
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="padding:10px 22px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('pengunjung.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

@endsection

