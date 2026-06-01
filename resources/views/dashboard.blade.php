<x-app-layout title="Dashboard - Alur Confused" icon='<i data-lucide="layout-dashboard" class="me-3"></i> Dashboard'>
    
    <div class="container-fluid px-3 py-3">
        <!-- 4 KPI Cards -->
        <div class="row g-4 mb-4">
            <!-- Total SKU Barang -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.05em;">Total SKU Barang</span>
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #f1f5f9;">
                                <i data-lucide="package" style="width: 18px; height: 18px; color: #475569;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 fw-bold text-dark" style="font-size: 24px;">{{ number_format($totalBarang ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0 text-muted" style="font-size: 12px;">Total variasi barang aktif</p>
                    </div>
                </div>
            </div>

            <!-- Nilai Aset Persediaan -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.05em;">Nilai Aset Persediaan</span>
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #ecfdf5;">
                                <i data-lucide="badge-dollar-sign" style="width: 18px; height: 18px; color: #10b981;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 fw-bold text-dark" style="font-size: 24px;">Rp {{ number_format($totalNilaiPersediaan ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0 text-muted" style="font-size: 12px;">Estimasi nilai modal stok aktif</p>
                    </div>
                </div>
            </div>

            <!-- Total Volume Pesanan -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.05em;">Total Pesanan</span>
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #f0f9ff;">
                                <i data-lucide="shopping-cart" style="width: 18px; height: 18px; color: #0284c7;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 fw-bold text-dark" style="font-size: 24px;">{{ number_format($totalPesanan ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0 text-muted" style="font-size: 12px;">Akumulasi pesanan keluar</p>
                    </div>
                </div>
            </div>

            <!-- Total Penjualan -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.05em;">Total Penjualan</span>
                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #fef3c7;">
                                <i data-lucide="trending-up" style="width: 18px; height: 18px; color: #d97706;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 fw-bold text-dark" style="font-size: 24px;">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0 text-muted" style="font-size: 12px;">Total pendapatan kotor</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Column Analytics -->
        <div class="row g-4">
            <!-- Left Side: Line Chart & Recent Activity -->
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-4">
                    <!-- Chart Card -->
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">Analisis Tren Pesanan</h5>
                                <p class="text-muted mb-0" style="font-size: 12px;">Statistik jumlah pesanan masuk bulanan</p>
                            </div>
                            <span class="text-secondary" style="font-size: 11px; font-weight: 500;">Tahun 2026</span>
                        </div>
                        <div class="card-body px-4 pb-4 pt-2">
                            <div style="height: 250px; width: 100%;">
                                <canvas id="orderChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Table Card -->
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">Daftar Transaksi Terkini</h5>
                                <p class="text-muted mb-0" style="font-size: 12px;">5 Pesanan penjualan terakhir</p>
                            </div>
                            <a href="{{ route('pesanan.index') }}" class="btn btn-sm btn-light border" style="font-size: 11px; text-transform: none;">Lihat Semua</a>
                        </div>
                        <div class="card-body p-0 mt-3">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0" style="border-top: 1px solid #e2e8f0;">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="ps-4 text-secondary text-uppercase fw-semibold" style="font-size: 11px; padding: 12px 16px;">No Pesanan</th>
                                            <th class="text-secondary text-uppercase fw-semibold" style="font-size: 11px; padding: 12px 16px;">Toko Pembeli</th>
                                            <th class="text-secondary text-uppercase fw-semibold" style="font-size: 11px; padding: 12px 16px;">Platform</th>
                                            <th class="text-secondary text-uppercase fw-semibold" style="font-size: 11px; padding: 12px 16px;">Tanggal</th>
                                            <th class="pe-4 text-end text-secondary text-uppercase fw-semibold" style="font-size: 11px; padding: 12px 16px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pesananTerbaru as $pesanan)
                                            @php
                                                $platform = strtolower($pesanan->toko->platform ?? 'offline');
                                                if (str_contains($platform, 'shopee')) {
                                                    $badgeStyle = 'background: rgba(238, 77, 45, 0.08); color: #ee4d2d;';
                                                } elseif (str_contains($platform, 'tokopedia')) {
                                                    $badgeStyle = 'background: rgba(3, 172, 14, 0.08); color: #03ac0e;';
                                                } elseif (str_contains($platform, 'lazada')) {
                                                    $badgeStyle = 'background: rgba(16, 20, 150, 0.08); color: #1014be;';
                                                } else {
                                                    $badgeStyle = 'background: rgba(100, 116, 139, 0.08); color: #475569;';
                                                }
                                            @endphp
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td class="ps-4 fw-semibold text-dark" style="font-size: 13px; padding: 12px 16px;">{{ $pesanan->no_pesanan }}</td>
                                                <td class="text-muted" style="font-size: 13px; padding: 12px 16px;">{{ $pesanan->toko->nama_toko ?? '-' }}</td>
                                                <td style="padding: 12px 16px;">
                                                    <span class="badge rounded px-2 py-1" style="{{ $badgeStyle }} font-size: 9px; font-weight: 600; text-transform: uppercase;">
                                                        {{ $pesanan->toko->platform ?? 'OFFLINE' }}
                                                    </span>
                                                </td>
                                                <td class="text-muted" style="font-size: 13px; padding: 12px 16px;">
                                                    {{ $pesanan->tanggal_keluar ? $pesanan->tanggal_keluar->format('d M Y') : '-' }}
                                                </td>
                                                <td class="pe-4 text-end fw-bold text-dark" style="font-size: 13px; padding: 12px 16px;">
                                                    Rp {{ number_format($pesanan->total ?? 0, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted" style="font-size: 13px;">Belum ada pesanan terbaru.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Low Stock Warnings & Quick Actions -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">
                    <!-- Low Stock Widget -->
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">Pantauan Stok Kritis</h5>
                                <p class="text-muted mb-0" style="font-size: 12px;">Persediaan di bawah safety stock</p>
                            </div>
                            @if($barangMenipis > 0)
                                <span class="badge bg-danger rounded-pill px-2.5 py-1" style="font-size: 9px; font-weight: 600; color: #ffffff;">{{ $barangMenipis }} Barang</span>
                            @else
                                <span class="badge bg-success rounded-pill px-2.5 py-1" style="font-size: 9px; font-weight: 600; color: #ffffff;">Stok Aman</span>
                            @endif
                        </div>
                        <div class="card-body px-4 pb-4 pt-3">
                            <div class="d-flex flex-column gap-3 overflow-auto" style="max-height: 250px; padding-right: 4px;">
                                @forelse($barangMenipisDetail as $item)
                                    @php
                                        if ($item->stock == 0) {
                                            $badgeText = 'Habis';
                                            $badgeClass = 'bg-danger text-white';
                                        } else {
                                            $badgeText = 'Kritis';
                                            $badgeClass = 'bg-warning text-dark';
                                        }
                                    @endphp
                                    <div class="p-3 rounded border d-flex justify-content-between align-items-center bg-light" style="border-color: #f1f5f9 !important;">
                                        <div style="max-width: 70%;">
                                            <span class="fw-bold text-dark d-block text-truncate" style="font-size: 12px;">{{ $item->barang->nama_barang }}</span>
                                            <span class="text-secondary d-block mt-0.5" style="font-size: 10px; font-family: monospace;">SKU: {{ $item->barang->kode_barang }}</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ $badgeClass }} mb-1" style="font-size: 8px; font-weight: 600; text-transform: uppercase;">{{ $badgeText }}</span>
                                            <span class="d-block fw-bold text-dark" style="font-size: 12px;">{{ $item->stock }} <span class="text-muted fw-normal" style="font-size: 10px;">/ {{ $item->safety_stock }}</span></span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-muted">
                                        <i data-lucide="shield-check" class="d-block mx-auto mb-2 text-success" style="width: 32px; height: 32px;"></i>
                                        <span class="small fw-semibold text-dark">Seluruh Stok di Batas Aman</span>
                                    </div>
                                @endforelse
                            </div>
                            
                            @if($barangMenipis > 0)
                                <div class="mt-4 pt-3 border-top" style="border-color: #e2e8f0;">
                                    <a href="{{ route('barang_masuk.create') }}" class="btn btn-sm btn-primary w-100 py-2 d-flex align-items-center justify-content-center" style="font-size: 11px; font-weight: 600;">
                                        <i data-lucide="package-plus" class="me-2" style="width: 14px; height: 14px;"></i> Lakukan Restock Barang
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Access Grid -->
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">Navigasi Aksi Cepat</h5>
                            <p class="text-muted mb-0" style="font-size: 12px;">Pintasan aksi transaksi operasional</p>
                        </div>
                        <div class="card-body p-4 pt-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('pesanan.create') }}" class="d-flex flex-column align-items-center p-3 rounded text-decoration-none border text-center transition bg-light" style="border-color: #e2e8f0; color: #475569; border-radius: 8px;">
                                        <div class="d-flex align-items-center justify-content-center rounded mb-2 bg-white border" style="width: 36px; height: 36px; border-radius: 6px;">
                                            <i data-lucide="plus-square" class="text-primary" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 11px;">Pesanan Baru</span>
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="{{ route('barang_masuk.create') }}" class="d-flex flex-column align-items-center p-3 rounded text-decoration-none border text-center transition bg-light" style="border-color: #e2e8f0; color: #475569; border-radius: 8px;">
                                        <div class="d-flex align-items-center justify-content-center rounded mb-2 bg-white border" style="width: 36px; height: 36px; border-radius: 6px;">
                                            <i data-lucide="package-plus" class="text-success" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 11px;">Barang Masuk</span>
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="{{ route('penyesuaian_persediaan.create') }}" class="d-flex flex-column align-items-center p-3 rounded text-decoration-none border text-center transition bg-light" style="border-color: #e2e8f0; color: #475569; border-radius: 8px;">
                                        <div class="d-flex align-items-center justify-content-center rounded mb-2 bg-white border" style="width: 36px; height: 36px; border-radius: 6px;">
                                            <i data-lucide="file-text" class="text-warning" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 11px;">Stok Opname</span>
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="{{ route('barang.create') }}" class="d-flex flex-column align-items-center p-3 rounded text-decoration-none border text-center transition bg-light" style="border-color: #e2e8f0; color: #475569; border-radius: 8px;">
                                        <div class="d-flex align-items-center justify-content-center rounded mb-2 bg-white border" style="width: 36px; height: 36px; border-radius: 6px;">
                                            <i data-lucide="plus-circle" class="text-secondary" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 11px;">Tambah Barang</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('orderChart').getContext('2d');
            
            // Clean Slate Blue Color Scheme (Stripe-like professional color)
            const mainColor = '#0284c7'; // clean slate sky-blue
            
            // Subtle gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, 'rgba(2, 132, 199, 0.12)');
            gradient.addColorStop(1, 'rgba(2, 132, 199, 0.00)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pesanan Masuk',
                        data: [
                            {{ $chartData[0] ?? 0 }},
                            {{ $chartData[1] ?? 0 }},
                            {{ $chartData[2] ?? 0 }},
                            {{ $chartData[3] ?? 0 }},
                            {{ $chartData[4] ?? 0 }},
                            {{ $chartData[5] ?? 0 }},
                            {{ $chartData[6] ?? 0 }},
                            {{ $chartData[7] ?? 0 }},
                            {{ $chartData[8] ?? 0 }},
                            {{ $chartData[9] ?? 0 }},
                            {{ $chartData[10] ?? 0 }},
                            {{ $chartData[11] ?? 0 }}
                        ],
                        borderColor: mainColor,
                        backgroundColor: gradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: mainColor,
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: mainColor,
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#e2e8f0',
                            padding: 10,
                            cornerRadius: 6,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Pesanan';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawTicks: false
                            },
                            border: {
                                dash: [4, 4],
                                color: 'transparent'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    family: 'sans-serif'
                                },
                                stepSize: 5
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                color: '#e2e8f0'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    family: 'sans-serif'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>