<x-app-layout title="Return Pesanan - Alur Confused" icon='<i data-lucide="rotate-ccw" class="me-3"></i> Return Pesanan'>
    <div class="container-fluid">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">
                    Data Return Pesanan
                </h4>
                <p class="text-muted mb-0" style="font-size: 14px;">
                    Kelola data return pesanan
                </p>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="success-alert">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="error-alert">
                {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Button + Search --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('return_pesanan.create') }}"
                   class="btn btn-primary"
                   style="background: linear-gradient(90deg, #4AC8EA 0%, #4AC8EA 100%); border:none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus"
                           style="margin-right:8px;width:20px;height:20px;"></i>
                        Tambah Return Pesanan
                    </p>
                </a>
            </div>

            <div class="col-md-2"></div>

            <div class="col-md-4 text-end">
                <div class="custom-search-container">
                    <input type="text"
                           id="customSearch"
                           class="custom-search-input"
                           placeholder="Search">

                    <i data-lucide="search"
                       class="custom-search-icon"
                       style="width:18px;height:18px;"></i>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm"
             style="background: var(--color-background); border-radius:12px;">
            <div class="card-body" style="padding:1.5rem;">
                <div class="table-responsive">
                    <table id="returnPesananTable"
                           class="table table-striped table-hover align-middle">

                        <thead>
                            <tr>
                                <th>No Return</th>
                                <th>Tanggal Return</th>
                                <th>No Pesanan</th>
                                <th>Toko</th>
                                <th>Status</th>
                                <th>Total Item</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($returnPesanans as $returnPesanan)
                                <tr class="clickable-row"
                                    data-href="{{ route('return_pesanan.show', $returnPesanan->id) }}"
                                    style="cursor:pointer;">

                                    <td>
                                        {{ $returnPesanan->no_return }}
                                    </td>

                                    <td>
                                        {{ $returnPesanan->tanggal_return->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        {{ $returnPesanan->no_pesanan }}
                                    </td>

                                    <td>
                                        {{ ucfirst($returnPesanan->toko) }}
                                    </td>

                                    <td>
                                        @if($returnPesanan->status == 'selesai')
                                            <span class="badge bg-success">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Belum Selesai
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $returnPesanan->details->sum('qty') }} pcs
                                    </td>

                                    <td>
                                        {{ $returnPesanan->keterangan ?? '-' }}
                                    </td>

                                    <td>
                                        <div class="btn-group">

                                            <a href="{{ route('return_pesanan.edit', $returnPesanan->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i data-lucide="edit"
                                                   style="width:18px;height:18px;"></i>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $returnPesanan->id }})">
                                                <i data-lucide="trash-2"
                                                   style="width:18px;height:18px;"></i>
                                            </button>

                                            <form id="deleteForm{{ $returnPesanan->id }}"
                                                  action="{{ route('return_pesanan.destroy', $returnPesanan->id) }}"
                                                  method="POST"
                                                  style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    if ($.fn.DataTable.isDataTable('#returnPesananTable')) {
        $('#returnPesananTable').DataTable().destroy();
    }

    let table = $('#returnPesananTable').DataTable({
        responsive: true,
        pageLength: 10,
        searching: true,
        dom: 'lrtip',
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [7] }
        ],
        language: {
            zeroRecords: "Tidak ditemukan data",
            emptyTable: "Belum ada data",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            }
        }
    });

    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    $(document).on('click', '.clickable-row', function(e) {
        if ($(e.target).closest('.btn').length) {
            return;
        }

        window.location = $(this).data('href');
    });

    setTimeout(function() {
        let alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
});


function confirmDelete(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah yakin ingin menghapus return pesanan ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>

@include('components.table-styles')
</x-app-layout>