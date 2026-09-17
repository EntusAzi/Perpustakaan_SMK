<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Perpustakaan SMK Negeri 1 Tirtamulya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <img src="{{ asset('assets/images/Logo Sekolah_No_Bg.png') }}" alt="Logo">
        </div>
        <div class="brand-name">SMK Negri 1 Tirtamulya</div>
        <div class="brand-subtitle">Kelola Buku & Peminjaman</div>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    Buku
                </a>
            </li>
            <li>
                <a href="{{ route('peminjam.index') }}" class="{{ request()->routeIs('peminjam.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Peminjam
                </a>
            </li>
            <li>
               <a href="{{ route('pengunjung.index') }}" class="{{ request()->routeIs('pengunjung.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Pengunjung
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    Laporan
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-user" style="justify-content: space-between;">
        <a href="{{ route('profil.index') }}" title="Lihat Profil Saya" style="display: flex; align-items: center; gap: 10px; overflow: hidden; text-decoration: none; flex: 1;">
            <div class="user-avatar-placeholder" style="flex-shrink: 0; overflow: hidden;">
                @if(auth()->user()->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->avatar))
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                @endif
            </div>
            <div class="user-info" style="overflow: hidden;">
                <div class="user-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff;">{{ auth()->user()->name ?? 'Petugas' }}</div>
                <div class="user-role">{{ auth()->user()->jabatan ?? 'Petugas Perpustakaan' }}</div>
            </div>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" title="Keluar / Logout" style="background: none; border: none; color: rgba(255,255,255,0.45); cursor: pointer; padding: 4px; display: flex; align-items: center; transition: color 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='rgba(255,255,255,0.45)'">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </form>
    </div>
</aside>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOPBAR -->
    <header class="topbar">
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-right">
            <form action="" method="GET" class="search-bar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="q" placeholder="Cari di perpustakaan..." value="{{ request('q') }}">
            </form>
            <button class="notif-btn" title="Notifikasi">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </button>
            <div class="user-badge" style="position: relative;" onclick="toggleUserDropdown(event)">
                <div class="avatar-circle" style="overflow: hidden;">
                    @if(auth()->user()->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->avatar))
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                    @endif
                </div>
                <span>{{ auth()->user()->name ?? 'Petugas' }}</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>

                <div id="userDropdown" style="display: none; position: absolute; right: 0; top: 120%; background: #fff; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; min-width: 180px; z-index: 100; padding: 6px 0;">
                    <div style="padding: 8px 14px; border-bottom: 1px solid #f1f5f9; font-size: 12px; color: #64748b;">
                        Masuk sebagai<br><strong style="color: #0f172a;">{{ auth()->user()->username ?? auth()->user()->email ?? 'Petugas' }}</strong>
                    </div>
                    <a href="{{ route('profil.index') }}" style="display: flex; align-items: center; gap: 8px; padding: 9px 14px; font-size: 13px; color: #334155; text-decoration: none; border-bottom: 1px solid #f8fafc; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='none'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Profil Saya
                    </a>
                    <a href="{{ route('profil.edit') }}" style="display: flex; align-items: center; gap: 8px; padding: 9px 14px; font-size: 13px; color: #334155; text-decoration: none; border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='none'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; padding: 10px 14px; background: none; border: none; font-size: 13px; color: #ef4444; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- PAGE BODY -->
    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
function toggleUserDropdown(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }
}
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>
@stack('scripts')
</body>
</html>
