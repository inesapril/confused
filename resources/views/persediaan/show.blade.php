<x-app-layout title="Detail Persediaan - Alur Confused"
    icon='<i data-lucide="package-search" class="me-3"></i> Detail Persediaan'>

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 style="font-weight:600;">
                Detail Persediaan
            </h4>

            <p class="text-muted mb-0">
                Riwayat pergerakan stok
            </p>
        </div>

        <div class="col-md-6 text-end">
            <a href="{{ route('persediaan.index') }}"
               class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="me-1"></i>
                Kembali
            </a>
        </div>
    </div>


    {{-- INFO BARANG --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-3">
                    <small class="text-muted">
                        Nama Barang
                    </small>

                    <h5>
                        {{ $persediaan->barang->nama_barang }}
                    </h5>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Harga Jual
                    </small>

                    <h5>
                        Rp {{ number_format($persediaan->barang->harga,0,',','.') }}
                    </h5>
                </div>

                @if(Auth::user()->hasRole('Owner'))
                <div class="col-md-3">
                    <small class="text-muted">
                        Harga Beli
                    </small>

                    <h5>
                        Rp {{ number_format($persediaan->barang->harga_beli,0,',','.') }}
                    </h5>
                </div>
                @endif

                <div class="col-md-3">
                    <small class="text-muted">
                        Safety Stock
                    </small>

                    <h5>
                        {{ $persediaan->safety_stock }}
                    </h5>
                </div>

            </div>

        </div>
    </div>


    {{-- TABEL RIWAYAT --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h6 class="mb-3">
                Riwayat Perubahan Stok
            </h6>

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Qty</th>
                            <th>Stok</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($riwayat as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}
                            </td>

                            <td>
                                {{ $item['jenis'] }}
                            </td>

                            <td>
                                {{ $item['qty'] }}
                            </td>

                            <td>
                                <strong>
                                    {{ $item['stok'] }}
                                </strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6"
                                class="text-center text-muted py-4">
                                Belum ada riwayat stok
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>


@push('scripts')
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush

</x-app-layout>