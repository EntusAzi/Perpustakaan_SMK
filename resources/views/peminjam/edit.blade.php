@extends('layouts.app')

@section('title', 'Edit Peminjam')
@section('page-title', 'Edit Peminjam')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('peminjam.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali
    </a>
</div>

<div class="form-card">
    <h2 style="font-size:16px;font-weight:700;margin-bottom:20px;">Edit Data Peminjam</h2>

    <form method="POST" action="{{ route('peminjam.update', $peminjam) }}">
        @csrf @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                       value="{{ old('nama', $peminjam->nama) }}" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Tipe *</label>
                <select name="tipe" class="form-control form-select" required>
                    <option value="siswa" {{ old('tipe', $peminjam->tipe) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru"  {{ old('tipe', $peminjam->tipe) === 'guru'  ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Kelas / Jabatan</label>
                <input type="text" name="kelas_jabatan" class="form-control" value="{{ old('kelas_jabatan', $peminjam->kelas_jabatan) }}">
            </div>
            <div class="form-group">
                <label class="form-label">NIS / NIP</label>
                <input type="text" name="nis_nip" class="form-control" value="{{ old('nis_nip', $peminjam->nis_nip) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $peminjam->telepon) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $peminjam->alamat) }}">
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('peminjam.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

@endsection
