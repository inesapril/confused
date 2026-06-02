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

        <a href="{{ route('persediaan.exportPdf') }}" target="_blank" class="btn btn-info">
            <i data-lucide="download"></i>
            Format Stok Opname
        </a>

        <!-- Search and Add Button Row -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('penyesuaian_persediaan.create') }}" class="btn btn-primary" style="background: linear-gradient(90deg, #4AC8EA 0%, #4AC8EA 100%); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Stok Opname
                    </p>
                </a>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4 text-end">
                <div class="custom-search-container">
                    <input type="text" id="customSearch" class="custom-search-input" placeholder="Search">
                    <i data-lucide="search" class="custom-search-icon" style="width: 18px; height: 18px;"></i>
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
</x-app-layout>
