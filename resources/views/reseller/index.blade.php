<x-app-layout title="reseller - Alur Confused" icon='<i data-lucide="box" class="me-3"></i> reseller'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Data reseller</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Kelola data reseller inventory</p>
            </div>
            <div class="col-md-6 text-end">
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Action -->
        <div class="card border-0 shadow-sm mb-4"
            style="
                background: var(--color-background);
                border-radius: 18px;
            ">

            <div class="card-body p-4">

                <!-- Tombol Utama -->
                <div class="mb-4">

                    <a href="{{ route('reseller.create') }}"
                        class="btn px-4 action-btn"
                        style="
                            background: var(--color-foreground);
                            color: var(--color-background);
                            border: none;
                            border-radius: 12px;
                            font-weight: 600;
                            white-space: nowrap;
                        ">

                        <i data-lucide="plus"
                            class="me-2"
                            style="width:18px;height:18px;">
                        </i>

                        Tambah Reseller

                    </a>

                </div>

                <!-- Utilitas -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <!-- Kiri -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">

                        <a href="{{ route('reseller.exportBarang') }}"
                            class="btn btn-success action-btn">

                            <i data-lucide="download"
                                class="me-2"
                                style="width:16px;height:16px;">
                            </i>

                            Export Reseller

                        </a>

                        <form action="{{ route('reseller.importBarang') }}"
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

                                Import Reseller

                            </button>

                        </form>

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

        <!-- Table Card -->
         <div class="card border-0 shadow-sm"
        style="
            background: var(--color-background);
            border-radius: 18px;
            overflow: hidden;
        ">
            <div class="card-body" style="padding: 1.5rem;">
                <div class="table-responsive">
                    <table id="ResellerTable" class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Reseller</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($resellers as $reseller)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $reseller->nama_reseller }}
                                    </td>

                                    <td>
                                        {{ $reseller->alamat ?? '-' }}
                                    </td>

                                    <td>
                                        <div class="btn-group">

                                            <a href="{{ route('reseller.edit', $reseller->id) }}"
                                            class="btn btn-warning btn-sm">

                                                <i data-lucide="edit"></i>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $reseller->id }})">

                                                <i data-lucide="trash-2"></i>
                                            </button>

                                            <form id="deleteForm{{ $reseller->id }}"
                                                action="{{ route('reseller.destroy', $reseller->id) }}"
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
    min-width: 120px;
    height: 36px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 6px;
    white-space: nowrap;

    font-size: 11px !important;
    padding: 0 12px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for all scripts to load
    setTimeout(function() {
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            try {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#resellerTable')) {
                    $('#ResellerTable').DataTable().destroy();
                }
                
                var table = $('#resellerTable').DataTable({
                    responsive: true,
                    pageLength: 50,
                    lengthMenu: [[50, 100, -1], [50, 100, "Semua"]],
                    searching: true,
                    dom: 'lrtip', // Hide default search box
                    language: {
                        processing: "Sedang memproses...",
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ entri",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                        infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                        infoPostFix: "",
                        loadingRecords: "Sedang memuat...",
                        zeroRecords: "Tidak ditemukan data yang sesuai",
                        emptyTable: "Tidak ada data yang tersedia pada tabel ini",
                        paginate: {
                            first: "Pertama",
                            previous: "‹",
                            next: "›",
                            last: "Terakhir"
                        }
                    },
                    columnDefs: [
                        { orderable: false, targets: [3] }
                    ],
                    order: [[0, 'asc']]
                });

                // Custom search functionality
                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Search icon click functionality
                $('.custom-search-icon').on('click', function() {
                    $('#customSearch').focus();
                });
            } catch (error) {
                console.error('DataTable initialization error:', error);
            }
        }
    }, 1000);

    // Auto-hide alert
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
});

function confirmDelete(resellerId) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus reseller ini?',
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
            document.getElementById('deleteForm' + resellerId).submit();
        }
    });
}
</script>

@include('components.table-styles')
</x-app-layout>
