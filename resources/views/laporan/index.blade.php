<x-app-layout title="Laporan - Alur Confused" icon='<i data-lucide="file-chart-column" class="me-3"></i> Laporan'>
    <div class="container-fluid py-4">

        <div class="card border-0 shadow-sm mb-4" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-body" style="padding: 1.5rem;">

                <h6 class="mb-3" style="font-weight: 600;">
                    Filter Laporan
                </h6>

                <form method="GET" action="{{ route('laporan.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">

                        {{-- Jenis laporan --}}
                        <div class="col-md-3">
                            <label class="form-label small text-muted fw-semibold">Jenis Laporan</label>
                            <select name="jenis" id="jenisLaporan" class="form-select">
                                <option value="">Pilih Jenis</option>
                                <option value="supplier" {{ request('jenis') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                                <option value="reseller" {{ request('jenis') == 'reseller' ? 'selected' : '' }}>Reseller</option>
                                <option value="return" {{ request('jenis') == 'return' ? 'selected' : '' }}>Return</option>
                                <option value="pesanan" {{ request('jenis') == 'pesanan' ? 'selected' : '' }}>Pesanan</option>
                                <option value="stok_menipis" {{ request('jenis') == 'stok_menipis' ? 'selected' : '' }}>Stok Menipis</option>
                            </select>
                        </div>

                        {{-- Supplier --}}
                        <div class="col-md-3" id="supplierFilter" style="display: none;">
                            <label class="form-label small text-muted fw-semibold">Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">Semua Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Reseller --}}
                        <div class="col-md-3" id="resellerFilter" style="display: none;">
                            <label class="form-label small text-muted fw-semibold">Reseller</label>
                            <select name="reseller_id" class="form-select">
                                <option value="">Semua Reseller</option>
                                @foreach($resellers as $reseller)
                                    <option value="{{ $reseller->id }}" {{ request('reseller_id') == $reseller->id ? 'selected' : '' }}>
                                        {{ $reseller->nama_reseller }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Toko --}}
                        <div class="col-md-3" id="tokoFilter" style="display: none;">
                            <label class="form-label small text-muted fw-semibold">Toko</label>
                            <select name="toko_id" class="form-select">
                                <option value="">Semua Toko</option>
                                @foreach($tokos as $toko)
                                    <option value="{{ $toko->id }}" {{ request('toko_id') == $toko->id ? 'selected' : '' }}>
                                        {{ $toko->nama_toko }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal mulai --}}
                        <div class="col-md-3">
                            <label class="form-label small text-muted fw-semibold">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>

                        {{-- Tanggal akhir --}}
                        <div class="col-md-3">
                            <label class="form-label small text-muted fw-semibold">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="col-md-3 ms-auto text-end">
                            @if($data->count() > 0)
                                <a href="{{ route('laporan.exportPdf', request()->query()) }}" 
                                target="_blank" 
                                class="btn btn-primary w-100 shadow-sm d-inline-flex align-items-center justify-content-center" 
                                style="background-color: var(--color-primary, #4f46e5); border-color: var(--color-primary, #4f46e5); pointer-events: auto;">
                                    <i data-lucide="download" class="me-2" style="width: 16px; height: 16px;"></i> 
                                    <span>Export PDF</span>
                                </a>
                            @endif
                        </div>

                    </div>
                </form>

            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="laporanTable" class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                @if($jenis == 'supplier' || $jenis == 'reseller' || $jenis == 'pesanan' || $jenis == 'return')
                                    <th>Tanggal</th>
                                @endif

                                @if($jenis == 'supplier') <th>Supplier</th> @endif
                                @if($jenis == 'reseller') <th>Reseller</th> @endif
                                @if($jenis == 'pesanan')  <th>Toko</th> @endif

                                @if($jenis == 'stok_menipis')
                                    <th>Nama Barang</th>
                                    <th class="text-end">Stok Sekarang</th>
                                    <th class="text-end">Safety Stock</th>
                                @else
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Jumlah</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            {{-- BARANG MASUK --}}
                            @if($jenis == 'supplier')
                                @foreach($data as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->barangMasuk->tanggal_masuk)->format('d-m-Y') }}</td>
                                        <td>{{ $item->barangMasuk->supplier->nama_supplier }}</td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="font-monospace text-end fw-semibold text-dark">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- BARANG KELUAR --}}
                            @if($jenis == 'reseller')
                                @php $rowNum = 1; @endphp
                                @foreach($data as $keluar)
                                    @foreach($keluar->details as $detail)
                                        <tr>
                                            <td>{{ $rowNum++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->format('d-m-Y') }}</td>
                                            <td>{{ $keluar->reseller->nama_reseller }}</td>
                                            <td>{{ $detail->barang->nama_barang }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td class="font-monospace text-end fw-semibold text-dark">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif

                            {{-- RETURN --}}
                            @if($jenis == 'return')
                                @php $rowNum = 1; @endphp
                                @foreach($data as $retur)
                                    @foreach($retur->details as $detail)
                                        <tr>
                                            <td>{{ $rowNum++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($retur->tanggal_return)->format('d-m-Y') }}</td> 
                                            <td>{{ $detail->barang->nama_barang }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->barang->harga, 0, ',', '.') }}</td>
                                            <td class="font-monospace text-end fw-semibold text-dark">Rp {{ number_format($detail->qty * $detail->barang->harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif

                            {{-- PESANAN --}}
                            @if($jenis == 'pesanan')
                                @php $rowNum = 1; @endphp
                                @foreach($data as $pesanan)
                                    @foreach($pesanan->details as $detail)
                                        <tr>
                                            <td>{{ $rowNum++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pesanan->tanggal_keluar)->format('d-m-Y') }}</td>
                                            <td>{{ $pesanan->toko->nama_toko ?? '-' }}</td>
                                            <td>{{ $detail->barang->nama_barang }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td class="font-monospace text-end fw-semibold text-dark">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif

                            {{-- STOK MENIPIS --}}
                            @if($jenis == 'stok_menipis')
                                @foreach($data as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td class="text-end">{{ $item->stock }}</td>
                                        <td class="text-end">{{ $item->safety_stock }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- TOTAL BLOCK --}}
                @if($jenis != 'stok_menipis' && isset($total))
                    <div class="d-flex justify-content-end mt-3 border-top pt-3">
                        <h5 style="font-weight: 600;">
                            Total: <span class="text-primary font-monospace">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </h5>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFilters() {
            let jenis = $('#jenisLaporan').val();

            $('#supplierFilter').hide();
            $('#resellerFilter').hide();
            $('#tokoFilter').hide();

            if (jenis === 'supplier') {
                $('#supplierFilter').show();
            } else if (jenis === 'reseller') {
                $('#resellerFilter').show();
            } else if (jenis === 'pesanan') {
                $('#tokoFilter').show();
            }
        }

        $(document).ready(function () {
            toggleFilters();

            $('#jenisLaporan').on('change', function () {
                toggleFilters();
            });

            $('select, input[type="date"]').on('change', function () {
                $(this).closest('form').submit();
            });

            if ($.fn.DataTable.isDataTable('#laporanTable')) {
                $('#laporanTable').DataTable().destroy();
            }

            let rowCount = $('#laporanTable tbody tr').length;

            $('#laporanTable').DataTable({
                language: {
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                pageLength: 50,
                paging: rowCount > 50,
                searching: false,
                responsive: true
            });

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @endpush
</x-app-layout>