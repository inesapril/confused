<x-app-layout title="Detail Pesanan - Alur Confused" icon='<i data-lucide="eye" class="me-3"></i> Detail Penyesuaian'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Detail Pesanan</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">{{ $pesanan->no_pesanan }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary me-2">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
                <a href="{{ route('pesanan.edit', $pesanan->id) }}" class="btn btn-warning">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="edit-3" style="margin-right: 8px; width: 20px; height: 20px;"></i> Edit
                    </p>
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Info Card -->
            <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
                <div class="card-body" style="padding: 2rem;">
                    <h6 class="mb-4"
                        style="color: var(--color-foreground); font-weight: 600;">
                        Informasi Pesanan
                    </h6>

                    <div class="row g-4">

                        <!-- No Pesanan -->
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-2">
                                No. Barang Keluar
                            </small>

                            <span class="badge text-dark"
                                style="background-color: rgba(74, 200, 234, 0.15);
                                padding: 8px 12px;
                                border-radius: 8px;
                                font-size: 14px;">
                                {{ $pesanan->no_pesanan }}
                            </span>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-2">
                                Tanggal Barang Keluar
                            </small>

                            <strong style="color: var(--color-foreground);">
                                {{ $pesanan->tanggal_keluar->format('d F Y') }}
                            </strong>
                        </div>

                        <!-- Toko -->
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-2">
                                Toko
                            </small>

                            <strong style="color: var(--color-foreground);">
                                {{ $pesanan->toko->nama_toko }}
                            </strong>
                        </div>

                        <!-- Qty -->
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-2">
                                Qty
                            </small>

                            <span class="badge bg-info">
                                {{ $pesanan->details->sum('qty') }} pcs
                            </span>
                        </div>

                        @if($pesanan->keterangan)
                        <div class="col-12">
                            <small class="text-muted d-block mb-2">
                                Keterangan
                            </small>

                            <p style="color: var(--color-foreground); margin-bottom: 0;">
                                {{ $pesanan->keterangan }}
                            </p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Detail Items -->
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
                    <div class="card-body" style="padding: 2rem;">
                        <h6 class="mb-3" style="color: var(--color-foreground); font-weight: 600;">Detail Barang</h6>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr style="background-color: rgba(74, 200, 234, 0.1);">
                                        <th style="border:none; padding:16px;">Nama Barang</th>
                                        <th style="border:none; padding:16px; text-align:center;">Harga</th>
                                        <th style="border:none; padding:16px; text-align:center;">Satuan</th>
                                        <th style="border:none; padding:16px; text-align:center;">Stok Keluar</th>
                                        <th style="border:none; padding:16px; text-align:center;">Jumlah</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($pesanan->details as $detail)
                                        <tr>
                                            <td style="border:none; padding:16px;">
                                                {{ $detail->barang->nama_barang }}
                                            </td>

                                            <td style="border:none; padding:16px; text-align:center;">
                                                Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                            </td>

                                            <td style="border:none; padding:16px; text-align:center;">
                                                {{ $detail->barang->satuan }}
                                            </td>

                                            <td style="border:none; padding:16px; text-align:center;">
                                                <span class="badge bg-info">
                                                    {{ $detail->qty }}
                                                </span>
                                            </td>

                                            <td style="border:none; padding:16px; text-align:center;">
                                                <strong>
                                                    Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr style="background-color: rgba(74, 200, 234, 0.08);">
                                        <td colspan="4" class="text-end fw-bold" style="padding:16px;">
                                            Total
                                        </td>
                                        <td class="fw-bold text-center" style="padding:16px;">
                                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .badge {
                font-size: 12px;
                padding: 6px 10px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Lucide icons
                lucide.createIcons();
            });
        </script>
    @endpush
</x-app-layout>
