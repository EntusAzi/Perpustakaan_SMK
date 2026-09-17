@extends('layouts.app')

@section('title', 'Catat Peminjaman' . ($selectedPeminjam ? ' - ' . $selectedPeminjam->nama : ''))
@section('page-title', 'Catat Peminjaman Buku')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ $selectedPeminjam ? route('peminjam.show', $selectedPeminjam) : route('peminjaman.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        {{ $selectedPeminjam ? 'Kembali ke Detail Peminjam' : 'Kembali ke Data Peminjaman' }}
    </a>
</div>

<div class="form-card" style="max-width: 820px;">
    <h2 style="font-size:16px;font-weight:700;margin-bottom:20px;">
        Form Peminjaman Buku {{ $selectedPeminjam ? 'untuk ' . $selectedPeminjam->nama : '' }}
    </h2>

    <form method="POST" action="{{ route('peminjaman.store') }}" id="formPeminjaman" onsubmit="return validasiSebelumSubmit()">
        @csrf

        {{-- JIKA PEMINJAM SUDAH DIPILIH DARI HALAMAN DETAIL --}}
        @if($selectedPeminjam)
        <input type="hidden" name="peminjam_id" value="{{ $selectedPeminjam->id }}">
        <input type="hidden" name="from_peminjam" value="1">

        <div class="form-group" style="background:#f0fdfa; border:1px solid #99f6e4; border-radius:10px; padding:16px; margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <span style="font-size:12px; font-weight:700; color:#0f766e; text-transform:uppercase; letter-spacing:0.04em; display:flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Peminjam Terpilih (Otomatis)
                </span>
                <a href="{{ route('peminjaman.create') }}" style="font-size:12px; color:#0284c7; text-decoration:none; font-weight:600;">
                    ✕ Ganti Peminjam
                </a>
            </div>

            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:44px; height:44px; border-radius:50%; background:#0f766e; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:18px; flex-shrink:0;">
                    {{ strtoupper(substr($selectedPeminjam->nama, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700; font-size:15px; color:#0f172a; display:flex; align-items:center; gap:8px;">
                        {{ $selectedPeminjam->nama }}
                        <span class="badge badge-{{ $selectedPeminjam->tipe }}">{{ ucfirst($selectedPeminjam->tipe) }}</span>
                    </div>
                    <div style="font-size:12.5px; color:#64748b; margin-top:2px;">
                        @if($selectedPeminjam->kelas_jabatan)
                            <span>Kelas/Jabatan: <strong style="color:#334155;">{{ $selectedPeminjam->kelas_jabatan }}</strong></span>
                        @endif
                        @if($selectedPeminjam->nis_nip)
                            <span style="margin-left:8px;">&bull; NIS/NIP: <strong style="color:#334155;">{{ $selectedPeminjam->nis_nip }}</strong></span>
                        @endif
                        @if($selectedPeminjam->telepon)
                            <span style="margin-left:8px;">&bull; Telp: <strong style="color:#334155;">{{ $selectedPeminjam->telepon }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        {{-- JIKA DIAKSES DARI MENU UMUM, TAMPILKAN PILIHAN SELECT --}}
        <div class="form-group">
            <label class="form-label">Peminjam <span style="color:#ef4444">*</span></label>
            <select name="peminjam_id" class="form-control form-select {{ $errors->has('peminjam_id') ? 'is-invalid' : '' }}" required>
                <option value="">-- Pilih Peminjam --</option>
                @foreach($peminjam as $p)
                <option value="{{ $p->id }}" {{ old('peminjam_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }} ({{ $p->kelas_jabatan ?? ucfirst($p->tipe) }})
                </option>
                @endforeach
            </select>
            @error('peminjam_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        @endif

        {{-- ========================================================= --}}
        {{-- BAGIAN PENCARIAN BUKU (MENDUKUNG BANYAK BUKU SEKALIGUS)   --}}
        {{-- ========================================================= --}}
        <div class="form-group" style="position:relative; margin-bottom:20px;">
            <label class="form-label" style="font-weight:600; display:flex; justify-content:space-between; align-items:center;">
                <span>Cari & Pilih Buku yang Dipinjam (Bisa Lebih dari 1) <span style="color:#ef4444">*</span></span>
                <span id="badge_total_buku" style="font-size:11.5px; font-weight:500; color:#0f766e; background:#ccfbf1; padding:2px 8px; border-radius:12px;">
                    {{ $buku->count() }} buku tersedia
                </span>
            </label>

            {{-- Input Hidden Container untuk menampung buku_ids[] --}}
            <div id="hidden_buku_inputs"></div>

            {{-- Input Pencarian Teks (Searching Box) --}}
            <div id="area_pencarian_buku" style="margin-bottom:12px;">
                <div style="position:relative;">
                    <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#64748b; display:flex; align-items:center; pointer-events:none;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    <input type="text" id="input_cari_buku" class="form-control"
                           style="padding-left:40px; padding-right:32px; height:42px; font-size:13.5px; border:1.5px solid #cbd5e1; border-radius:8px;"
                           placeholder="Ketik judul buku, pengarang, no. inventaris, ID, atau nomor rak..."
                           autocomplete="off"
                           oninput="filterDaftarBuku(this.value)"
                           onfocus="bukaDaftarBuku()">

                    <button type="button" id="btn_clear_buku"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; display:none; padding:4px; font-size:14px;"
                            onclick="clearInputPencarianBuku()" title="Bersihkan">✕</button>
                </div>

                {{-- Container Daftar Hasil Pencarian --}}
                <div id="container_hasil_buku"
                     style="display:none; position:relative; margin-top:6px; max-height:220px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); z-index:50;">
                    <div id="list_buku_items">
                        @foreach($buku as $b)
                        <div class="item-buku-cari"
                             id="create_item_buku_{{ $b->id }}"
                             data-id="{{ $b->id }}"
                             data-judul="{{ strtolower($b->judul) }}"
                             data-penulis="{{ strtolower($b->penulis ?? '') }}"
                             data-penerbit="{{ strtolower($b->penerbit ?? '') }}"
                             data-idbuku="{{ strtolower($b->id_buku ?? '') }}"
                             data-noinv="{{ strtolower($b->no_inventaris ?? '') }}"
                             data-rak="{{ strtolower($b->nomor_rak ?? '') }}"
                             data-kelas="{{ $b->kelas }}"
                             onclick="tambahBukuKeDaftar({{ json_encode($b) }})"
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
                    <div id="empty_buku_msg" style="display:none; padding:18px; text-align:center; color:#94a3b8; font-size:13px;">
                        Buku tidak ditemukan. Silakan ketik judul atau pengarang lain.
                    </div>
                </div>
            </div>

            {{-- DAFTAR BUKU YANG AKAN DIPINJAM (KERANJANG / MULTI BUKU) --}}
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span style="font-weight:700; font-size:13px; color:#0f172a; display:flex; align-items:center; gap:6px;">
                        <span>📚 Daftar Buku yang Akan Dipinjam</span>
                        <span id="badge_count_selected" class="badge badge-primary" style="font-size:11px; padding:2px 8px; background:#0f766e;">0 Buku</span>
                    </span>
                    <span style="font-size:11.5px; color:#64748b;">Klik buku pada hasil pencarian di atas untuk menambah</span>
                </div>

                <div id="box_list_selected" style="border:1.5px dashed #cbd5e1; border-radius:10px; padding:12px; min-height:80px; max-height:260px; overflow-y:auto; background:#f8fafc;">
                    <div id="empty_selected_msg" style="padding:16px; text-align:center; color:#94a3b8; font-size:12.5px;">
                        Belum ada buku yang dipilih. Silakan cari judul buku di atas dan klik <strong>+ Tambah</strong>.
                    </div>
                    <div id="cards_selected_wrapper" style="display:flex; flex-direction:column; gap:8px;"></div>
                </div>

                <div id="error_buku_required" style="display:none; color:#ef4444; font-size:12px; margin-top:5px; font-weight:600;">
                    ⚠️ Silakan cari dan pilih minimal 1 buku yang akan dipinjam terlebih dahulu.
                </div>
                @error('buku_ids') <div class="invalid-feedback" style="display:block;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tanggal Pinjam <span style="color:#ef4444">*</span></label>
                <input type="date" name="tanggal_pinjam" class="form-control {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                       value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                @error('tanggal_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Batas Pengembalian <span style="color:#ef4444">*</span></label>
                <input type="date" name="tanggal_kembali" class="form-control {{ $errors->has('tanggal_kembali') ? 'is-invalid' : '' }}"
                       value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}" required>
                @error('tanggal_kembali') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Catatan Peminjaman</label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan (keperluan tugas sekolah, mata pelajaran, dll)...">{{ old('catatan') }}</textarea>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; flex-wrap:wrap; gap:10px;">
            <span id="footer_summary_txt" style="font-size:13px; color:#475569; font-weight:500;">
                0 buku dipilih untuk dipinjam
            </span>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
                <a href="{{ $selectedPeminjam ? route('peminjam.show', $selectedPeminjam) : route('peminjaman.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </div>
    </form>
</div>

<script>
    const allBooksData = @json($buku);
    let selectedBooks = [];

    function filterDaftarBuku(query) {
        const q = (query || '').toLowerCase().trim();
        const items = document.querySelectorAll('.item-buku-cari');
        const emptyMsg = document.getElementById('empty_buku_msg');
        const btnClear = document.getElementById('btn_clear_buku');
        const container = document.getElementById('container_hasil_buku');

        container.style.display = 'block';
        btnClear.style.display = q.length > 0 ? 'block' : 'none';

        let matchCount = 0;
        items.forEach(el => {
            const judul = el.getAttribute('data-judul') || '';
            const penulis = el.getAttribute('data-penulis') || '';
            const penerbit = el.getAttribute('data-penerbit') || '';
            const idbuku = el.getAttribute('data-idbuku') || '';
            const noinv = el.getAttribute('data-noinv') || '';
            const rak = el.getAttribute('data-rak') || '';

            if (!q || judul.includes(q) || penulis.includes(q) || penerbit.includes(q) || idbuku.includes(q) || noinv.includes(q) || rak.includes(q)) {
                el.style.display = 'flex';
                matchCount++;
            } else {
                el.style.display = 'none';
            }
        });

        emptyMsg.style.display = matchCount === 0 ? 'block' : 'none';
    }

    function bukaDaftarBuku() {
        const container = document.getElementById('container_hasil_buku');
        container.style.display = 'block';
    }

    function clearInputPencarianBuku() {
        const input = document.getElementById('input_cari_buku');
        input.value = '';
        filterDaftarBuku('');
        input.focus();
    }

    function tambahBukuKeDaftar(buku) {
        const exists = selectedBooks.find(b => String(b.id) === String(buku.id));
        if (exists) {
            alert('Buku "' + (buku.judul || 'ini') + '" sudah ditambahkan ke daftar pinjam!');
            return;
        }

        selectedBooks.push(buku);
        renderSelectedBooks();
        document.getElementById('error_buku_required').style.display = 'none';

        // Feedback animasi
        const el = document.getElementById('create_item_buku_' + buku.id);
        if (el) {
            el.style.background = '#ccfbf1';
            setTimeout(() => { el.style.background = '#ffffff'; }, 350);
        }
    }

    function hapusBukuDariDaftar(id) {
        selectedBooks = selectedBooks.filter(b => String(b.id) !== String(id));
        renderSelectedBooks();
    }

    function renderSelectedBooks() {
        const wrapper = document.getElementById('cards_selected_wrapper');
        const emptyMsg = document.getElementById('empty_selected_msg');
        const badgeCount = document.getElementById('badge_count_selected');
        const footerSummary = document.getElementById('footer_summary_txt');
        const hiddenContainer = document.getElementById('hidden_buku_inputs');
        const boxContainer = document.getElementById('box_list_selected');

        hiddenContainer.innerHTML = '';
        wrapper.innerHTML = '';

        badgeCount.innerText = selectedBooks.length + ' Buku';
        footerSummary.innerText = selectedBooks.length + ' buku dipilih untuk dipinjam';

        if (selectedBooks.length === 0) {
            emptyMsg.style.display = 'block';
            boxContainer.style.borderColor = '#cbd5e1';
            boxContainer.style.background = '#f8fafc';
            return;
        }

        emptyMsg.style.display = 'none';
        boxContainer.style.borderColor = '#99f6e4';
        boxContainer.style.background = '#f0fdfa';

        selectedBooks.forEach((b, idx) => {
            // Hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'buku_ids[]';
            input.value = b.id;
            hiddenContainer.appendChild(input);

            // Card item
            const card = document.createElement('div');
            card.style.cssText = 'background:#ffffff; border:1px solid #99f6e4; border-radius:8px; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px; box-shadow:0 1px 2px rgba(0,0,0,0.04);';
            card.innerHTML = `
                <div style="display:flex; align-items:center; gap:10px; flex:1;">
                    <div style="width:26px; height:26px; border-radius:50%; background:#0f766e; color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0;">
                        ${idx + 1}
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                            <strong style="font-size:13.5px; color:#0f172a;">${b.judul || '-'}</strong>
                            <span style="font-size:10.5px; font-weight:700; background:#f1f5f9; color:#0f766e; padding:1px 5px; border-radius:4px; font-family:monospace;">
                                ${b.id_buku || ('BK-' + String(b.id).padStart(4, '0'))}
                            </span>
                            ${b.kelas ? `<span style="font-size:10px; font-weight:600; background:#e0f2fe; color:#0284c7; padding:1px 5px; border-radius:4px;">Kelas ${b.kelas}</span>` : ''}
                        </div>
                        <div style="font-size:12px; color:#64748b; margin-top:2px;">
                            ${b.penulis ? b.penulis : '-'} ${b.nomor_rak ? `&bull; <span style="color:#0f766e; font-weight:600;">📍 ${b.nomor_rak}</span>` : ''}
                            &bull; <span style="color:#15803d; font-weight:600;">Stok: ${b.stok_tersedia ?? b.stok}</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="hapusBukuDariDaftar('${b.id}')" class="btn btn-outline btn-sm" style="font-size:11.5px; padding:4px 10px; color:#ef4444; border-color:#fecaca; background:#fff;" title="Hapus dari daftar">
                    ✕ Hapus
                </button>
            `;
            wrapper.appendChild(card);
        });
    }

    function validasiSebelumSubmit() {
        if (selectedBooks.length === 0) {
            document.getElementById('error_buku_required').style.display = 'block';
            document.getElementById('input_cari_buku').focus();
            return false;
        }
        return true;
    }

    // Tutup dropdown hasil pencarian jika klik di luar
    document.addEventListener('click', function(e) {
        const area = document.getElementById('area_pencarian_buku');
        const container = document.getElementById('container_hasil_buku');
        if (area && !area.contains(e.target) && container) {
            container.style.display = 'none';
        }
    });
</script>

@endsection
