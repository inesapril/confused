<x-app-layout title="Detail Laporan - Alur Confused" icon='<i data-lucide="file-text" class="me-3"></i> Detail Laporan'>
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-md-6">
                <h4 class="mb-0" style="font-weight:600;">
                    Detail Laporan
                </h4>
                <p class="text-muted">
                    Jenis laporan:
                    <b>{{ ucfirst(str_replace('_',' ', $jenis)) }}</b>
                </p>
            </div>

            <div class="col-md-6 text-end">
                <a href="{{ route('laporan.index') }}"
                   class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>


        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>

                        @php
                            $grandTotal = 0;
                        @endphp

                        @forelse($data as $index => $item)

                            @php
                                $qty = $item->jumlah ?? $item->qty ?? 0;
                                $harga = $item->harga ?? 0;
                                $subtotal = $qty * $harga;
                                $grandTotal += $subtotal;
                            @endphp

                            <tr>
                                <td>{{ $index+1 }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse(
                                        $item->tanggal ??
                                        $item->tanggal_masuk ??
                                        $item->tanggal_keluar
                                    )->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $item->barang->nama_barang ?? '-' }}
                                </td>

                                <td>
                                    {{ $qty }}
                                </td>

                                <td>
                                    Rp {{ number_format($harga,0,',','.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($subtotal,0,',','.') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Tidak ada data
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">
                                    TOTAL
                                </th>
                                <th>
                                    Rp {{ number_format($grandTotal,0,',','.') }}
                                </th>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>