@extends('layouts.app')

@section('title', 'Edit Profil Pengguna')
@section('page-title', 'Edit Profil Pengguna')

@section('content')

<div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
    <div>
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-secondary); margin-bottom: 4px;">
            <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Dashboard</a>
            <span>/</span>
            <a href="{{ route('profil.index') }}" style="color: var(--text-secondary); text-decoration: none;">Profil Saya</a>
            <span>/</span>
            <span style="color: var(--text-primary); font-weight: 600;">Edit Profil</span>
        </div>
        <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
            Perbarui data identitas pribadi, foto avatar, atau ubah kata sandi akun Anda.
        </p>
    </div>

    {{-- Tombol Kembali ke Halaman Profil --}}
    <div>
        <a href="{{ route('profil.index') }}" class="btn btn-outline btn-sm" style="gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Kembali ke Halaman Profil
        </a>
    </div>
</div>

<div class="form-card" style="max-width: 820px; padding: 28px;">
    @if (isset($errors) && $errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px 16px; margin-bottom: 22px;">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #b91c1c; font-size: 13.5px; margin-bottom: 4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Terjadi kesalahan pada input Anda:
            </div>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #dc2626;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. FOTO PROFIL / AVATAR --}}
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: var(--teal); color: white; font-size: 12px; font-weight: 700;">1</span>
                Foto Profil / Avatar
            </div>

            <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                {{-- Preview Avatar --}}
                <div style="position: relative; width: 84px; height: 84px; flex-shrink: 0;">
                    @if($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar))
                        <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                             style="width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 2.5px solid var(--teal); box-shadow: var(--shadow);">
                    @else
                        <div id="avatarPlaceholder" style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #00c9a7 0%, #008f77 100%); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 700; color: white; border: 2.5px solid #b2ede4; box-shadow: var(--shadow);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <img id="avatarPreview" src="" alt="Preview" style="display: none; width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 2.5px solid var(--teal); box-shadow: var(--shadow);">
                    @endif
                </div>

                {{-- Input File --}}
                <div style="flex: 1; min-width: 240px;">
                    <label class="form-label" style="margin-bottom: 4px;">Pilih Foto Baru</label>
                    <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-control" style="padding: 6px 10px; font-size: 13px;">
                    <div class="form-text" style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                        Format yang didukung: JPG, PNG, atau WEBP. Ukuran file maksimal: 2 MB.
                    </div>

                    @if($user->avatar)
                    <div style="margin-top: 8px;">
                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; color: #dc2626; cursor: pointer;">
                            <input type="checkbox" name="remove_avatar" value="1">
                            Hapus foto saat ini dan gunakan inisial nama
                        </label>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 2. INFORMASI IDENTITAS & AKUN --}}
        <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: var(--teal); color: white; font-size: 12px; font-weight: 700;">2</span>
                Informasi Akun & Data Diri
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" class="form-control {{ $errors?->has('name') ? 'is-invalid' : '' }}" 
                           value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap petugas" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Username <span style="color:#ef4444">*</span></label>
                    <input type="text" name="username" class="form-control {{ $errors?->has('username') ? 'is-invalid' : '' }}" 
                           value="{{ old('username', $user->username) }}" placeholder="Contoh: admin_smk" required>
                    <div class="form-text">Hanya huruf, angka, strip (-), dan garis bawah (_).</div>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row" style="margin-bottom: 0;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Alamat Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" class="form-control {{ $errors?->has('email') ? 'is-invalid' : '' }}" 
                           value="{{ old('email', $user->email) }}" placeholder="nama@smkn1tirtamulya.sch.id" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Jabatan / Posisi</label>
                    <input type="text" name="jabatan" class="form-control {{ $errors?->has('jabatan') ? 'is-invalid' : '' }}" 
                           value="{{ old('jabatan', $user->jabatan) }}" placeholder="Contoh: Kepala Pustaka / Staff Pengelola">
                    @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- 3. GANTI KATA SANDI (OPSIONAL) --}}
        <div id="keamanan" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 28px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <div style="font-size: 14px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #64748b; color: white; font-size: 12px; font-weight: 700;">3</span>
                    Ganti Kata Sandi (Opsional)
                </div>
                <span style="font-size: 12px; color: #64748b; font-style: italic;">Kosongkan jika tidak ingin mengganti</span>
            </div>

            <p style="font-size: 12.5px; color: var(--text-secondary); margin-bottom: 16px;">
                Jika Anda ingin memperbarui password, masukkan password saat ini lalu masukkan password baru minimal 6 karakter.
            </p>

            <div class="form-group">
                <label class="form-label">Password Saat Ini</label>
                <div style="position: relative;">
                    <input type="password" name="current_password" id="current_password" 
                           class="form-control {{ $errors?->has('current_password') ? 'is-invalid' : '' }}" 
                           placeholder="Masukkan password saat ini">
                    <button type="button" onclick="togglePass('current_password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-row" style="margin-bottom: 0;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Password Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" 
                               class="form-control {{ $errors?->has('password') ? 'is-invalid' : '' }}" 
                               placeholder="Minimal 6 karakter">
                        <button type="button" onclick="togglePass('password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control" 
                               placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePass('password_confirmation', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOMBOL AKSI FORM --}}
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; border-top: 1px solid var(--border); padding-top: 20px;">
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; box-shadow: 0 2px 6px rgba(0, 201, 167, 0.3);">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('profil.index') }}" class="btn btn-outline" style="padding: 10px 20px;">
                    Batal
                </a>
            </div>

            <a href="{{ route('profil.index') }}" style="font-size: 13px; color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                &larr; Kembali ke Halaman Profil
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Live preview avatar saat memilih file baru
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                    avatarPreview.style.display = 'block';
                    if (avatarPlaceholder) {
                        avatarPlaceholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Toggle lihat/sembunyikan password
    function togglePass(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            btn.style.color = 'var(--teal)';
        } else {
            input.type = 'password';
            btn.style.color = '#94a3b8';
        }
    }
</script>
@endpush

@endsection
