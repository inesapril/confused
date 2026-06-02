<x-app-layout title="Barang - Alur Confused" icon='<i data-lucide="box" class="me-3"></i> Barang'>

    <div class="container-fluid">

        <!-- Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0"
                role="alert"
                id="success-alert"
                style="border-radius: 12px;">

                {{ session('success') }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"
                role="alert"
                id="error-alert"
                style="border-radius: 12px; background-color: #f8d7da; color: #721c24;">

                {{ session('error') }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>
            </div>
        @endif

        <!-- Info -->
                <div class="mb-4">
                    <h5 class="mb-1 fw-bold"
                        style="color: var(--color-foreground);">
                        Data Barang
                    </h5>

                    <p class="mb-0 text-muted">
                        Kelola data barang inventory
                    </p>
                </div>

        <!-- Header Action -->
        <div class="card border-0 shadow-sm mb-4"
            style="
                background: var(--color-background);
                border-radius: 18px;
            ">

            <div class="card-body p-4">

                <!-- Tombol Utama -->
                <div class="mb-4">

                    <a href="{{ route('barang.create') }}"
                        class="btn px-4 action-btn"
                        style="
                            background: var(--color-foreground);
                            color: var(--color-background);
                            border: none;
                            border-radius: 12px;
                            height: 48px;
                            font-weight: 600;
                            white-space: nowrap;
                        ">

                        <i data-lucide="plus"
                            class="me-2"
                            style="width:18px;height:18px;">
                        </i>

                        Tambah Barang

                    </a>

                </div>

                <!-- Utilitas -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <!-- Kiri -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">

                        <!-- Export -->
                        <a href="{{ route('barang.export') }}"
                            class="btn btn-success action-btn">

                            <i data-lucide="download"
                                class="me-2"
                                style="width:16px;height:16px;">
                            </i>

                            Export Excel

                        </a>

                        <!-- Import -->
                        <form action="{{ route('barang.import') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="d-flex flex-wrap gap-2 align-items-center">

                            @csrf

                            <input type="file"
                                name="file"
                                required
                                class="form-control form-control-sm"
                                style="max-width:160px;">

                            <button type="submit"
                                class="btn btn-info action-btn">

                                <i data-lucide="upload"
                                    class="me-2"
                                    style="width:16px;height:16px;">
                                </i>

                                Import Excel

                            </button>

                        </form>

                        <!-- QR -->
                        <a href="{{ route('barang.label.index') }}"
                            class="btn btn-secondary action-btn">

                            <i data-lucide="qr-code"
                                class="me-2"
                                style="width:16px;height:16px;">
                            </i>

                            QR Code

                            </a>

                    </div>

                    <!-- Search -->
                    <div class="col-md-4 text-end">
                        <div class="custom-search-container">
                            <input type="text" id="customSearch" class="custom-search-input" placeholder="Search">
                            <i data-lucide="search" class="custom-search-icon" style="width: 18px; height: 18px;"></i>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Table -->
        <div class="card border-0 shadow-sm"
            style="
                background: var(--color-background);
                border-radius: 18px;
                overflow: hidden;
            ">

            <div class="card-body p-4">

                <div class="table-responsive">

                    <table id="barangTable"
                    class="table table-striped table-hover align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Harga Beli</th>
                                <th>Satuan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($barangs as $barang)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $barang->kode_barang }}
                                    </td>

                                    <td>
                                        {{ $barang->nama_barang }}
                                    </td>

                                    <td>
                                        {{ $barang->kategori }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        {{ $barang->satuan }}
                                    </td>

                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('barang.edit', $barang->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i data-lucide="edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $barang->id }})">
                                                <i data-lucide="trash-2"></i>
                                            </button>

                                            <form id="deleteForm{{ $barang->id }}"
                                                action="{{ route('barang.destroy', $barang->id) }}"
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

<style>
.action-btn{
    min-width: 150px;
    height: 45px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    white-space: nowrap;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    setTimeout(function () {

        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {

            try {

                if ($.fn.DataTable.isDataTable('#barangTable')) {
                    $('#barangTable').DataTable().destroy();
                }

                var table = $('#barangTable').DataTable({
                    responsive: true,
                    pageLength: 50,
                    lengthMenu: [[50, 100, -1], [50, 100, "Semua"]],
                    searching: true,
                    dom: 'lrtip',

                    language: {
                        processing: "Sedang memproses...",
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ entri",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                        infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                        loadingRecords: "Sedang memuat...",
                        zeroRecords: "Tidak ditemukan data",
                        emptyTable: "Belum ada data",
                        paginate: {
                            first: "Pertama",
                            previous: "‹",
                            next: "›",
                            last: "Terakhir"
                        }
                    },

                    columnDefs: [
                        { orderable: false, targets: [7] }
                    ],

                    order: [[0, 'asc']]
                });

                $('#customSearch').on('keyup', function () {
                    table.search(this.value).draw();
                });

            } catch (error) {

                console.error('DataTable initialization error:', error);

            }

        }

    }, 500);

    // Auto hide alert
    setTimeout(function () {

        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.transition = 'all .4s ease';
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                successAlert.remove();
            }, 400);
        }

        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            errorAlert.style.transition = 'all .4s ease';
            errorAlert.style.opacity = '0';
            errorAlert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                errorAlert.remove();
            }, 400);
        }

    }, 3000);

});

function confirmDelete(barangId) {

    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus barang ini?',
        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',

        customClass: {
            popup: 'swal-popup-poppins'
        }

    }).then((result) => {

        if (result.isConfirmed) {

            document.getElementById('deleteForm' + barangId).submit();

        }

    });

}
</script>

@include('components.table-styles')

</x-app-layout>