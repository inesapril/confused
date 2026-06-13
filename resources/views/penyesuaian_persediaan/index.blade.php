<x-app-layout title="Stok Opname - Alur Confused" icon='<i data-lucide="refresh-cw" class="me-3"></i> Stok Opname'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Data Stok Opname</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Kelola Stok Opname inventori</p>
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
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

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <!-- Kiri -->
                <div class="d-flex gap-2 align-items-center flex-wrap">

                    <a href="{{ route('penyesuaian_persediaan.create') }}"
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

                        Tambah Stok Opname

                    </a>

                    <a href="{{ route('persediaan.exportPdf') }}"
                        target="_blank"
                        class="btn btn-info action-btn">

                        <i data-lucide="download"
                            class="me-2"
                            style="width:16px;height:16px;">
                        </i>

                        Format Stok Opname
                    </a>

                </div>

                <!-- Kanan -->
                <div class="search-wrapper">
                    <div class="custom-search-container">
                        <i data-lucide="search" class="custom-search-icon"></i>

                        <input type="text"
                            id="customSearch"
                            class="custom-search-input"
                            placeholder="Search">
                    </div>
                </div>
                </div>
            </div>
        </div>  

        <!-- Table Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-body" style="padding: 1.5rem;">
                <div class="table-responsive">
                    <table id="penyesuaianTable" class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: var(--color-foreground);">No. Stok Opname</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Tanggal Stok Opname</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Keterangan</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Total Item</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penyesuaianPersediaans as $penyesuaian)
                                <tr class="clickable-row"
                                    data-href="{{ route('penyesuaian_persediaan.show', $penyesuaian->id) }}"
                                    style="cursor:pointer;">
                                    <td style="font-weight: 500;">{{ $penyesuaian->no_penyesuaian }}</td>
                                    <td>{{ $penyesuaian->tanggal_penyesuaian->format('d/m/Y') }}</td>
                                    <td>{{ $penyesuaian->keterangan ?? '-' }}</td>
                                    <td>{{ $penyesuaian->details->count() }} item</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('penyesuaian_persediaan.edit', $penyesuaian->id) }}" class="btn btn-sm btn-warning">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="edit" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $penyesuaian->id }})">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="trash-2" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </button>
                                            <form id="deleteForm{{ $penyesuaian->id }}" action="{{ route('penyesuaian_persediaan.destroy', $penyesuaian->id) }}" method="POST" style="display: none;">
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

    @push('styles')
        <x-table-styles />
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.clickable-row')
                .forEach(row => {
                    row.addEventListener('click', function(e) {

                        if (e.target.closest('.btn')) return;

                        window.location = this.dataset.href;
                    });
                });
                // Wait for all scripts to load
                setTimeout(function() {
                    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                        try {
                            // Auto-hide alerts after 5 seconds
                            setTimeout(function() {
                                $('.alert').fadeOut('slow');
                            }, 5000);

                            // Destroy existing DataTable if it exists
                            if ($.fn.DataTable.isDataTable('#penyesuaianTable')) {
                                $('#penyesuaianTable').DataTable().destroy();
                            }
                            
                            var table = $('#penyesuaianTable').DataTable({
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
                    } else {
                        console.error('jQuery or DataTables not loaded');
                    }
                }, 500); // Increased delay to ensure all resources are loaded

                // Initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            // Confirm delete function
            function confirmDelete(id) {
                if (confirm('Apakah Anda yakin ingin menghapus stok opname ini?')) {
                    document.getElementById('deleteForm' + id).submit();
                }
            }
        </script>
    @endpush

<style>
.search-wrapper{
    width:300px;
}

.custom-search-container{
    position:relative;
}

.custom-search-input{
    width:100%;
    height:45px;
    border:1px solid #dee2e6;
    border-radius:12px;
    padding-left:50px !important;
}

.custom-search-icon{
    position:absolute;
    left:16px;
    top:50%;
    transform:translateY(-50%);
    width:18px;
    height:18px;
    color:#6c757d;
    z-index:10;
    pointer-events:none;
}

.action-btn{
    min-width:180px;
    height:45px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    white-space:nowrap;
}
</style>
</x-app-layout>
