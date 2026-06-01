<x-app-layout title="Detail Stok Opname - Alur Confused" icon='<i data-lucide="eye" class="me-3"></i> Detail Stok Opname'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Detail Stok Opname</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">{{ $penyesuaianPersediaan->no_penyesuaian }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('penyesuaian_persediaan.index') }}" class="btn btn-outline-secondary me-2">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
                <a href="{{ route('penyesuaian_persediaan.edit', $penyesuaianPersediaan->id) }}" class="btn btn-warning">
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
                        Informasi Stok Opname
                    </h6>

                    <div class="row g-4">

                        <!-- No Penyesuaian -->
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-2">
                                No. Stok Opname
                            </small>

                            <span class="badge text-dark"
                                style="background-color: rgba(74, 200, 234, 0.15);
                                padding: 8px 12px;
                                border-radius: 8px;
                                font-size: 14px;">
                                {{ $penyesuaianPersediaan->no_penyesuaian }}
                            </span>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-2">
                                Tanggal Stok Opname
                            </small>

                            <strong style="color: var(--color-foreground);">
                                {{ $penyesuaianPersediaan->tanggal_penyesuaian->format('d F Y') }}
                            </strong>
                        </div>

                        <!-- Total Item -->
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-2">
                                Total Item
                            </small>

                            <span class="badge bg-info">
                                {{ $penyesuaianPersediaan->details->count() }} item
                            </span>
                        </div>

                        @if($penyesuaianPersediaan->keterangan)
                        <div class="col-12">
                            <small class="text-muted d-block mb-2">
                                Keterangan
                            </small>

                            <p style="color: var(--color-foreground); margin-bottom: 0;">
                                {{ $penyesuaianPersediaan->keterangan }}
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
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Nama Barang</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Stok Sistem</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Penyesuaian</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Stock Fisik</th>
                                    </tr>
                                </thead>
                                <tbody>
    @foreach($penyesuaianPersediaan->details as $detail)
        <tr>
            <td style="border: none; padding: 16px; color: var(--color-foreground);">
                {{ $detail->barang->nama_barang }}
            </td>

            {{-- stok sistem --}}
            <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                <span class="badge bg-secondary">
                    {{ number_format($detail->stok_sistem) }}
                </span>
            </td>

            {{-- penyesuaian --}}
            <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                <span class="badge {{ $detail->selisih >= 0 ? 'bg-success' : 'bg-danger' }}">
                    {{ $detail->selisih >= 0 ? '+' : '' }}
                    {{ number_format($detail->selisih) }}
                </span>
            </td>

            {{-- stok fisik --}}
            <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                <span class="badge bg-primary">
                    {{ number_format($detail->stok_fisik) }}
                </span>
            </td>
        </tr>
    @endforeach
</tbody>
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
