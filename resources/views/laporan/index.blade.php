@extends('layouts.app')

@section('title', 'Laporan & Analisis')
@section('page-title', 'Laporan & Analisis')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

<div class="period-bar">
    <form method="GET" action="{{ route('laporan.index') }}" style="display:flex;align-items:center;gap:10px;">
        <label style="font-size:13px;font-weight:600;color:var(--text-secondary);">Periode Laporan</label>
        <div class="period-selector">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <input type="date" name="start_date" value="{{ $startDate }}" style="border:none;background:transparent;outline:none;font-size:13px;cursor:pointer;">
            <span>–</span>
            <input type="date" name="end_date" value="{{ $endDate }}" style="border:none;background:transparent;outline:none;font-size:13px;cursor:pointer;">
        </div>
        <button type="submit" class="btn btn-outline btn-sm">Terapkan</button>
    </form>
    
    {{-- Tombol Export Excel & PDF Laporan Bulanan (Sirkulasi & Evaluasi) --}}
        <div style="display:flex;gap:8px;align-items:center;">
            <a href="{{ route('laporan.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline" style="gap:6px;font-size:12px;padding:6px 12px;color:#15803d;border-color:#bbf7d0;background:#f0fdf4;white-space:nowrap;" title="Export rekapitulasi sirkulasi dan evaluasi ke Excel (.xls)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('laporan.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-outline" style="gap:6px;font-size:12px;padding:6px 12px;color:#dc2626;border-color:#fecaca;background:#fef2f2;white-space:nowrap;" title="Cetak atau simpan rekapitulasi sirkulasi dan evaluasi ke file PDF resmi">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Cetak / PDF
            </a>
        </div>
</div>

<div class="laporan-grid">
    {{-- Laporan Bulanan Buku --}}
    <div class="laporan-card">
        <div class="laporan-card-title">Laporan Bulanan Buku</div>
        <div class="laporan-card-subtitle">Bulan Terakhir: {{ $totalBukuTerbaru }} Buku Baru</div>
        <div class="chart-container">
            <canvas id="chartBuku"></canvas>
        </div>
        <div style="margin-top:14px;">
            <a href="{{ route('buku.index') }}" class="btn btn-outline btn-sm">Lihat Detail</a>
        </div>
    </div>

    {{-- Laporan Bulanan Pengunjung --}}
    <div class="laporan-card">
        <div class="laporan-card-title">Laporan Bulanan Pengunjung</div>
        <div class="laporan-card-subtitle">Bulan Terakhir: {{ number_format($totalPengunjung) }} Pengunjung</div>
        <div class="chart-container">
            <canvas id="chartPengunjung"></canvas>
        </div>
        <div style="margin-top:14px;">
            <a href="{{ route('pengunjung.index') }}" class="btn btn-outline btn-sm">Lihat Detail</a>
        </div>
    </div>

    {{-- Laporan Bulanan Peminjam --}}
    <div class="laporan-card">
        <div class="laporan-card-title">Laporan Bulanan Peminjam</div>
        <div class="laporan-card-subtitle">Bulan Terakhir: {{ $totalPeminjaman }} Transaksi Aktif</div>
        <div class="chart-container">
            <canvas id="chartPeminjaman"></canvas>
        </div>
        <div style="margin-top:14px;">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">Lihat Detail</a>
        </div>
    </div>

    {{-- Rekap Seluruh Buku --}}
    <div class="laporan-card">
        <div class="laporan-card-title">Rekap Seluruh Buku</div>
        <div class="laporan-card-subtitle">Bulan Terakhir: {{ number_format($totalBuku) }} Judul Terdaftar</div>
        <div class="chart-container">
            <canvas id="chartRekap"></canvas>
        </div>
        <div style="margin-top:14px;">
            <a href="{{ route('buku.index') }}" class="btn btn-outline btn-sm">Lihat Detail</a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const TEAL_COLORS = [
    'rgba(0, 201, 167, 0.9)',
    'rgba(0, 201, 167, 0.65)',
    'rgba(0, 201, 167, 0.4)',
    'rgba(0, 201, 167, 0.25)',
    'rgba(0, 165, 137, 0.75)',
    'rgba(0, 165, 137, 0.5)',
];

const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9ca3af' } },
        y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, color: '#9ca3af' }, beginAtZero: true }
    }
};

// Chart Buku
const bukuData = @json($bukuBulanan);
new Chart(document.getElementById('chartBuku'), {
    type: 'bar',
    data: {
        labels: bukuData.map(d => d.label),
        datasets: [{ data: bukuData.map(d => d.count), backgroundColor: TEAL_COLORS, borderRadius: 4 }]
    },
    options: chartDefaults
});

// Chart Pengunjung
const pengunjungData = @json($pengunjungBulanan);
new Chart(document.getElementById('chartPengunjung'), {
    type: 'bar',
    data: {
        labels: pengunjungData.map(d => d.label),
        datasets: [{ data: pengunjungData.map(d => d.count), backgroundColor: TEAL_COLORS, borderRadius: 4 }]
    },
    options: chartDefaults
});

// Chart Peminjaman
const peminjamanData = @json($peminjamanBulanan);
new Chart(document.getElementById('chartPeminjaman'), {
    type: 'bar',
    data: {
        labels: peminjamanData.map(d => d.label),
        datasets: [{ data: peminjamanData.map(d => d.count), backgroundColor: TEAL_COLORS, borderRadius: 4 }]
    },
    options: chartDefaults
});

// Rekap Buku (Doughnut) dengan Warna Kontras Tinggi
const rekapData = @json($rekapBuku);
new Chart(document.getElementById('chartRekap'), {
    type: 'doughnut',
    data: {
        labels: rekapData.map(d => d.label),
        datasets: [{
            data: rekapData.map(d => d.count),
            backgroundColor: [
                '#0d9488', // LKS/Paket - Teal / Hijau Toska
                '#2563eb', // Referensi - Royal Blue / Biru Terang
                '#f59e0b', // Karya Fiksi - Amber / Oranye Cerah
                '#8b5cf6', // Umum - Purple / Ungu Violet
            ],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '62%',
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    font: { size: 12, weight: '500' },
                    color: '#374151',
                    padding: 14,
                    boxWidth: 14,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        }
    }
});
</script>
@endpush
