@extends('layouts.app')

@section('title', 'Detail Peminjam - ' . $peminjam->nama)
@section('page-title', 'Detail Peminjam')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('peminjam.index') }}" class="btn btn-outline btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;">
    {{-- INFO CARD PEMINJAM --}}
    <div class="card" style="padding:24px;height:fit-content;">
        <div style="text-align:center;margin-bottom:20px;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:26px;font-weight:700;color:white;">
                {{ strtoupper(substr($peminjam->nama, 0, 1)) }}
            </div>
            <h2 style="font-size:16px;font-weight:700;">{{ $peminjam->nama }}</h2>
            <span class="badge badge-{{ $peminjam->tipe }}" style="margin-top:6px;">{{ ucfirst($peminjam->tipe) }}</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--text-secondary);">Kelas/Jabatan</span>
                <span style="font-weight:600;">{{ $peminjam->kelas_jabatan ?? '-' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--text-secondary);">NIS/NIP</span>
                <span style="font-weight:600;">{{ $peminjam->nis_nip ?? '-' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--text-secondary);">Telepon</span>
                <span style="font-weight:600;">{{ $peminjam->telepon ?? '-' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--text-secondary);">Total Pinjam</span>
                <span style="font-weight:600;">{{ $peminjam->peminjaman->count() }} buku</span>
            </div>
        </div>
        <div style="margin-top:20px;display:flex;gap:8px;">
            <a href="{{ route('peminjam.edit', $peminjam) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">Edit</a>
        </div>
    </div>

    {{-- RIWAYAT PEMINJAMAN --}}
    <div class="card">
        <div class="card-header" style="display:flex; align-items:center; justify-content:space-between;">
            <h3 class="card-title">Riwayat Peminjaman</h3>
            <button type="button" onclick="bukaModalPinjamBuku()" class="btn btn-primary btn-sm" style="display:flex; align-items:center; gap:6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                + Pinjam Buku
            </button>
        </div>
        <div class="card-body table-container">
            @if($peminjam->peminjaman->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjam->peminjaman as $p)
                    <tr>
                        <td>
                            <span class="name-bold">{{ $p->buku->judul ?? '-' }}</span>
                            @if($p->buku?->nomor_rak)
                                <div style="font-size:11px; color:#0f766e;">📍 {{ $p->buku->nomor_rak }}</div>
                            @endif
                        </td>
                        <td>{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                        <td>{{ $p->tanggal_kembali?->format('d M Y') }}</td>
                        <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        <td>
                            @if($p->status === 'dipinjam')
                            <form method="POST" action="{{ route('peminjaman.kembalikan', $p) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm" style="background:#d1fae5;color:#065f46;">Kembalikan</button>
                            </form>
                            @else
                            <span style="color:var(--text-light);font-size:12px;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                <p>Belum ada riwayat peminjaman buku untuk peminjam ini</p>
                <button type="button" onclick="bukaModalPinjamBuku()" class="btn btn-primary btn-sm" style="margin-top:10px;">
                    + Catat Peminjaman Pertama
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL PINJAM BUKU (MENDUKUNG BANYAK BUKU & PENCARIAN)      --}}
{{-- ========================================================= --}}
<div id="modal_pinjam_buku" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(15,23,42,0.65); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; border-radius:12px; max-width:680px; width:100%; max-height:92vh; overflow-y:auto; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); position:relative;">
        
        {{-- Header Modal --}}
        <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; background:#f8fafc; border-top-left-radius:12px; border-top-right-radius:12px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; border-radius:8px; background:#0f766e; color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px;">
                    📖
                </div>
                <div>
                    <h3 style="font-size:15px; font-weight:700; color:#0f172a; margin:0;">Catat Peminjaman Buku (Bisa Lebih dari 1)</h3>
                    <div style="font-size:12px; color:#64748b;">
                        Peminjam: <strong style="color:#0f766e;">{{ $peminjam->nama }}</strong> ({{ $peminjam->kelas_jabatan ?? ucfirst($peminjam->tipe) }})
                    </div>
                </div>
            </div>
            <button type="button" onclick="tutupModalPinjamBuku()" style="background:none; border:none; font-size:20px; color:#94a3b8; cursor:pointer; padding:4px;">✕</button>
        </div>

        {{-- Form Body --}}
        <form method="POST" action="{{ route('peminjaman.store') }}" onsubmit="return validasiModalPinjam()" style="margin:0;">
            @csrf
            <input type="hidden" name="peminjam_id" value="{{ $peminjam->id }}">
            <input type="hidden" name="from_peminjam" value="1">

            {{-- Container input hidden untuk buku_ids[] --}}
            <div id="m_hidden_buku_inputs"></div>

            <div style="padding:20px;">
                {{-- Area Pencarian Buku (Searching - Bukan Dropdown) --}}
                <div class="form-group" style="position:relative; margin-bottom:14px;">
                    <label class="form-label" style="font-weight:600; display:flex; justify-content:space-between; align-items:center;">
                        <span>Cari Judul Buku yang Dipinjam <span style="color:#ef4444">*</span></span>
                        <span style="font-size:11.5px; font-weight:500; color:#0f766e; background:#ccfbf1; padding:2px 8px; border-radius:12px;">
                            {{ isset($buku) ? $buku->count() : 0 }} buku tersedia
                        </span>
                    </label>

                    {{-- Kotak Pencarian Teks --}}
                    <div style="position:relative;">
                        <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#64748b; display:flex; align-items:center; pointer-events:none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>
                        <input type="text" id="m_input_cari" class="form-control"
                               style="padding-left:38px; padding-right:32px; height:40px; font-size:13px; border:1.5px solid #cbd5e1; border-radius:8px;"
                               placeholder="Ketik judul buku, pengarang, nomor rak, atau ID buku..."
                               autocomplete="off"
                               oninput="mFilterDaftarBuku(this.value)">

                        <button type="button" id="m_btn_clear"
                                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; display:none; padding:4px; font-size:13px;"
                                onclick="mClearPencarian()" title="Bersihkan">✕</button>
                    </div>

                    {{-- Daftar Hasil Pencarian --}}
                    <div id="m_hasil_container" style="margin-top:6px; max-height:180px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
                        <div id="m_list_buku">
                            @if(isset($buku))
                            @foreach($buku as $b)
                            <div class="m-item-buku"
                                 id="m_item_buku_{{ $b->id }}"
                                 data-id="{{ $b->id }}"
                                 data-judul="{{ strtolower($b->judul) }}"
                                 data-penulis="{{ strtolower($b->penulis ?? '') }}"
                                 data-penerbit="{{ strtolower($b->penerbit ?? '') }}"
                                 data-idbuku="{{ strtolower($b->id_buku ?? '') }}"
                                 data-noinv="{{ strtolower($b->no_inventaris ?? '') }}"
                                 data-rak="{{ strtolower($b->nomor_rak ?? '') }}"
                                 onclick="mTambahBuku({{ json_encode($b) }})"
                                 style="padding:8px 12px; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:background 0.15s; display:flex; align-items:center; justify-content:space-between; gap:10px;"
                                 onmouseover="this.style.background='#f0fdfa'"
                                 onmouseout="this.style.background='#ffffff'">
                                <div style="flex:1;">
                                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:2px; flex-wrap:wrap;">
                                        <strong style="font-size:13px; color:#0f172a;">{{ $b->judul }}</strong>
                                        <span style="font-size:10px; font-weight:700; background:#f1f5f9; color:#0f766e; padding:1px 5px; border-radius:4px; font-family:monospace;">
                                            {{ $b->id_buku ?? 'BK-' . str_pad($b->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        @if($b->kelas)
                                        <span style="font-size:10px; font-weight:600; background:#e0f2fe; color:#0284c7; padding:1px 5px; border-radius:4px;">
                                            Kelas {{ $b->kelas }}
                                        </span>
                                        @endif
                                    </div>
                                    <div style="font-size:11.5px; color:#64748b;">
                                        {{ $b->penulis ?: '-' }}
                                        @if($b->nomor_rak)
                                        &bull; <span style="color:#0f766e;">📍 {{ $b->nomor_rak }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span class="badge badge-tersedia" style="font-size:10.5px;">
                                        Stok: {{ $b->stok_tersedia }}
                                    </span>
                                    <span style="font-size:12px; font-weight:600; color:#0f766e; background:#f0fdfa; border:1px solid #99f6e4; padding:2px 8px; border-radius:4px;">
                                        + Tambah
                                    </span>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div id="m_empty_buku_msg" style="display:none; padding:16px; text-align:center; color:#94a3b8; font-size:12.5px;">
                            Buku tidak ditemukan. Silakan ketik judul atau pengarang lain.
                        </div>
                    </div>
                </div>

                {{-- DAFTAR BUKU TERPILIH (MULTI BUKU) --}}
                <div style="margin-bottom:18px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <label class="form-label" style="font-weight:700; margin-bottom:0; font-size:13px; color:#0f172a; display:flex; align-items:center; gap:6px;">
                            <span>📚 Daftar Buku yang Akan Dipinjam</span>
                            <span id="m_badge_count" class="badge badge-primary" style="font-size:11px; padding:2px 8px; background:#0f766e;">0 Buku</span>
                        </label>
                        <span style="font-size:11.5px; color:#64748b;">Klik buku pada hasil pencarian di atas untuk menambah</span>
                    </div>

                    {{-- Kotak List Buku yang Dipilih --}}
                    <div id="m_box_list_terpilih" style="border:1.5px dashed #cbd5e1; border-radius:10px; padding:10px; min-height:80px; max-height:220px; overflow-y:auto; background:#f8fafc;">
                        <div id="m_empty_selected_notice" style="padding:16px; text-align:center; color:#94a3b8; font-size:12.5px;">
                            Belum ada buku yang dipilih. Silakan cari judul buku pada kotak di atas dan klik <strong>+ Tambah</strong>.
                        </div>
                        <div id="m_cards_buku_wrapper" style="display:flex; flex-direction:column; gap:8px;"></div>
                    </div>

                    <div id="m_error_buku" style="display:none; color:#ef4444; font-size:12px; margin-top:5px; font-weight:600;">
                        ⚠️ Silakan pilih minimal 1 buku yang akan dipinjam terlebih dahulu.
                    </div>
                </div>

                {{-- Tanggal Pinjam & Kembali --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                    <div>
                        <label class="form-label" style="font-size:12.5px; font-weight:600;">Tanggal Pinjam <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required style="font-size:13px; height:38px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:12.5px; font-weight:600;">Batas Pengembalian <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_kembali" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required style="font-size:13px; height:38px;">
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="form-group" style="margin-bottom:8px;">
                    <label class="form-label" style="font-size:12.5px; font-weight:600;">Catatan Peminjaman (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2" style="font-size:13px;" placeholder="Catatan tugas sekolah, mata pelajaran, atau keterangan khusus..."></textarea>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div style="padding:14px 20px; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border-bottom-left-radius:12px; border-bottom-right-radius:12px;">
                <span id="m_footer_summary" style="font-size:12.5px; color:#475569; font-weight:500;">
                    0 buku dipilih
                </span>
                <div style="display:flex; gap:10px;">
                    <button type="button" onclick="tutupModalPinjamBuku()" class="btn btn-outline btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="font-weight:700;">Simpan Peminjaman</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // State daftar buku terpilih untuk modal
    let mSelectedBooks = [];

    function bukaModalPinjamBuku() {
        const modal = document.getElementById('modal_pinjam_buku');
        if (modal) {
            modal.style.display = 'flex';
            const inputCari = document.getElementById('m_input_cari');
            if (inputCari) {
                setTimeout(() => inputCari.focus(), 100);
            }
        }
    }

    function tutupModalPinjamBuku() {
        const modal = document.getElementById('modal_pinjam_buku');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function mFilterDaftarBuku(query) {
        const q = (query || '').toLowerCase().trim();
        const items = document.querySelectorAll('.m-item-buku');
        const emptyMsg = document.getElementById('m_empty_buku_msg');
        const btnClear = document.getElementById('m_btn_clear');

        if (btnClear) btnClear.style.display = q.length > 0 ? 'block' : 'none';

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

        if (emptyMsg) emptyMsg.style.display = matchCount === 0 ? 'block' : 'none';
    }

    function mClearPencarian() {
        const input = document.getElementById('m_input_cari');
        if (input) {
            input.value = '';
            mFilterDaftarBuku('');
            input.focus();
        }
    }

    function mTambahBuku(buku) {
        // Cek apakah sudah ada dalam daftar
        const exists = mSelectedBooks.find(b => String(b.id) === String(buku.id));
        if (exists) {
            alert('Buku "' + (buku.judul || 'ini') + '" sudah ditambahkan ke daftar peminjaman!');
            return;
        }

        mSelectedBooks.push(buku);
        mRenderDaftarTerpilih();
        document.getElementById('m_error_buku').style.display = 'none';

        // Berikan feedback visual pada baris pencarian
        const el = document.getElementById('m_item_buku_' + buku.id);
        if (el) {
            el.style.background = '#ccfbf1';
            setTimeout(() => { el.style.background = '#ffffff'; }, 400);
        }
    }

    function mHapusBuku(id) {
        mSelectedBooks = mSelectedBooks.filter(b => String(b.id) !== String(id));
        mRenderDaftarTerpilih();
    }

    function mRenderDaftarTerpilih() {
        const wrapper = document.getElementById('m_cards_buku_wrapper');
        const emptyNotice = document.getElementById('m_empty_selected_notice');
        const badgeCount = document.getElementById('m_badge_count');
        const footerSummary = document.getElementById('m_footer_summary');
        const hiddenInputs = document.getElementById('m_hidden_buku_inputs');
        const boxContainer = document.getElementById('m_box_list_terpilih');

        hiddenInputs.innerHTML = '';
        wrapper.innerHTML = '';

        badgeCount.innerText = mSelectedBooks.length + ' Buku';
        footerSummary.innerText = mSelectedBooks.length + ' buku siap dipinjam';

        if (mSelectedBooks.length === 0) {
            emptyNotice.style.display = 'block';
            boxContainer.style.borderColor = '#cbd5e1';
            boxContainer.style.background = '#f8fafc';
            return;
        }

        emptyNotice.style.display = 'none';
        boxContainer.style.borderColor = '#99f6e4';
        boxContainer.style.background = '#f0fdfa';

        mSelectedBooks.forEach((b, idx) => {
            // Tambahkan hidden input untuk form submit
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'buku_ids[]';
            input.value = b.id;
            hiddenInputs.appendChild(input);

            // Render Card Buku
            const card = document.createElement('div');
            card.style.cssText = 'background:#ffffff; border:1px solid #99f6e4; border-radius:8px; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px; box-shadow:0 1px 2px rgba(0,0,0,0.04);';
            card.innerHTML = `
                <div style="display:flex; align-items:center; gap:10px; flex:1;">
                    <div style="width:26px; height:26px; border-radius:50%; background:#0f766e; color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0;">
                        ${idx + 1}
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                            <strong style="font-size:13px; color:#0f172a;">${b.judul || '-'}</strong>
                            <span style="font-size:10px; font-weight:700; background:#f1f5f9; color:#0f766e; padding:1px 5px; border-radius:4px; font-family:monospace;">
                                ${b.id_buku || ('BK-' + String(b.id).padStart(4, '0'))}
                            </span>
                            ${b.kelas ? `<span style="font-size:10px; font-weight:600; background:#e0f2fe; color:#0284c7; padding:1px 5px; border-radius:4px;">Kelas ${b.kelas}</span>` : ''}
                        </div>
                        <div style="font-size:11.5px; color:#64748b; margin-top:2px;">
                            ${b.penulis ? b.penulis : '-'} ${b.nomor_rak ? `&bull; <span style="color:#0f766e; font-weight:600;">📍 ${b.nomor_rak}</span>` : ''}
                        </div>
                    </div>
                </div>
                <button type="button" onclick="mHapusBuku('${b.id}')" class="btn btn-outline btn-sm" style="font-size:11px; padding:3px 8px; color:#ef4444; border-color:#fecaca; background:#fff;" title="Hapus dari daftar">
                    ✕ Hapus
                </button>
            `;
            wrapper.appendChild(card);
        });
    }

    function validasiModalPinjam() {
        if (mSelectedBooks.length === 0) {
            document.getElementById('m_error_buku').style.display = 'block';
            const input = document.getElementById('m_input_cari');
            if (input) input.focus();
            return false;
        }
        return true;
    }

    // Tutup modal jika klik di luar area dialog
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('modal_pinjam_buku');
        if (modal && e.target === modal) {
            tutupModalPinjamBuku();
        }
    });

    // Tutup modal jika menekan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            tutupModalPinjamBuku();
        }
    });
</script>

@endsection
