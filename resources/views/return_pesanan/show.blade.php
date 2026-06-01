```php
<x-app-layout title="Detail Return Pesanan - Alur Confused" icon='<i data-lucide="eye" class="me-3"></i> Detail Return'>
    <div class="container-fluid">

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0"
                    style="color: var(--color-foreground); font-weight: 600;">
                    Detail Return Pesanan
                </h4>

                <p class="text-muted mb-0" style="font-size: 14px;">
                    {{ $returnPesanan->no_return }}
                </p>
            </div>

            <div class="col-md-6 text-end">
                <a href="{{ route('return_pesanan.index') }}"
                   class="btn btn-outline-secondary me-2">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left"
                           style="margin-right: 8px; width: 20px; height: 20px;"></i>
                        Kembali
                    </p>
                </a>

                <a href="{{ route('return_pesanan.edit', $returnPesanan->id) }}"
                   class="btn btn-warning">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="edit-3"
                           style="margin-right: 8px; width: 20px; height: 20px;"></i>
                        Edit
                    </p>
                </a>
            </div>
        </div>


        <!-- Info Card -->
        <div class="card border-0 shadow-sm"
             style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">

                <h6 class="mb-4"
                    style="color: var(--color-foreground); font-weight: 600;">
                    Informasi Return Pesanan
                </h6>

                <div class="row g-4">

                    <div class="col-md-3">
                        <small class="text-muted d-block mb-2">
                            No Return
                        </small>

                        <span class="badge text-dark"
                              style="background-color: rgba(74, 200, 234, 0.15);
                                     padding: 8px 12px;
                                     border-radius: 8px;
                                     font-size: 14px;">
                            {{ $returnPesanan->no_return }}
                        </span>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block mb-2">
                            Tanggal Return
                        </small>

                        <strong>
                            {{ $returnPesanan->tanggal_return->format('d F Y') }}
                        </strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block mb-2">
                            No Pesanan
                        </small>

                        <strong>
                            {{ $returnPesanan->no_pesanan }}
                        </strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block mb-2">
                            Toko
                        </small>

                        <strong>
                            {{ ucfirst($returnPesanan->toko) }}
                        </strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block mb-2">
                            Status
                        </small>

                        <span class="badge {{ $returnPesanan->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst(str_replace('_', ' ', $returnPesanan->status)) }}
                        </span>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block mb-2">
                            Total Item
                        </small>

                        <span class="badge bg-info">
                            {{ $returnPesanan->details->count() }} item
                        </span>
                    </div>

                    @if($returnPesanan->keterangan)
                    <div class="col-12">
                        <small class="text-muted d-block mb-2">
                            Keterangan
                        </small>

                        <p style="margin-bottom:0;">
                            {{ $returnPesanan->keterangan }}
                        </p>
                    </div>
                    @endif

                </div>
            </div>
        </div>


        <!-- Detail Barang -->
        <div class="col-12 mt-4">
            <div class="card border-0 shadow-sm"
                 style="background: var(--color-background); border-radius: 16px;">
                <div class="card-body" style="padding: 2rem;">

                    <h6 class="mb-3"
                        style="color: var(--color-foreground); font-weight: 600;">
                        Detail Barang
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">

                            <thead>
                                <tr style="background-color: rgba(74, 200, 234, 0.1);">
                                    <th style="border:none; padding:16px;">
                                        Nama Barang
                                    </th>

                                    <th style="border:none; padding:16px; text-align:center;">
                                        Satuan
                                    </th>

                                    <th style="border:none; padding:16px; text-align:center;">
                                        Qty
                                    </th>

                                    <th style="border:none; padding:16px; text-align:center;">
                                        Kondisi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($returnPesanan->details as $detail)
                                    <tr>
                                        <td style="border:none; padding:16px;">
                                            {{ $detail->barang->nama_barang }}
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
                                            {{ ucfirst(str_replace('_', ' ', $detail->kondisi)) }}
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
    @endpush
</x-app-layout>
```
