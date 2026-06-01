<x-app-layout title="Persediaan - Alur Confused" icon='<i data-lucide="layers" class="me-3"></i> Persediaan'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Data Persediaan</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Kelola data persediaan barang</p>
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

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert" id="info-alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search Row -->
        <div class="row mb-3">
            <div class="col-md-6"></div>
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
                    <table id="persediaanTable" class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">No</th>
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">Nama Barang</th>
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">Harga Jual</th>
                                @if(Auth::user()->hasRole('Owner'))
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">Harga Beli</th>
                                @endif
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">Satuan</th>
                                <th colspan="2" style="color: var(--color-foreground); font-weight: 600; text-align: center; border-bottom: 1px solid #eee;">Persediaan</th>
                            </tr>
                            <tr>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Aman</th>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($persediaan as $index => $item)
                                <tr onclick="window.location='{{ route('persediaan.show', $item->id) }}'"
                                    style="cursor:pointer;">
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-weight: 500;">{{ $item->barang->nama_barang }}</td>
                                    <td>
                                        Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                                    </td>
                                    @if(Auth::user()->hasRole('Owner'))
                                    <td>
                                        Rp {{ number_format($item->barang->harga_beli, 0, ',', '.') }}
                                    </td>
                                    @endif
                                    <td>{{ $item->barang->satuan }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-{{ $item->getSafetyStockColor() }}">
                                            {{ $item->safety_stock }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-{{ $item->getSafetyStockColor() }}">
                                            {{ $item->stock }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center" style="padding: 3rem;">
                                        <i data-lucide="layers" style="width: 48px; height: 48px; color: #6c757d;"></i>
                                        <div class="mt-3">
                                            <h6 class="text-muted">Belum ada data persediaan</h6>
                                            <p class="text-muted mb-0">Data persediaan akan dibuat otomatis berdasarkan data barang</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    const infoAlert = document.getElementById('info-alert');
    
    if (successAlert) {
        successAlert.style.transition = 'opacity 0.5s';
        successAlert.style.opacity = '0';
        setTimeout(() => successAlert.remove(), 500);
    }
    
    if (errorAlert) {
        errorAlert.style.transition = 'opacity 0.5s';
        errorAlert.style.opacity = '0';
        setTimeout(() => errorAlert.remove(), 500);
    }
    
    if (infoAlert) {
        infoAlert.style.transition = 'opacity 0.5s';
        infoAlert.style.opacity = '0';
        setTimeout(() => infoAlert.remove(), 500);
    }
}, 5000);

// Delete confirmation with SweetAlert - REMOVED (Read-only mode)

// Initialize DataTable
$(document).ready(function() {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        try {
            setTimeout(function() {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#persediaanTable')) {
                    $('#persediaanTable').DataTable().destroy();
                }

                var table = $('#persediaanTable').DataTable({
                    dom: 'lrtip',
                    language: {
                        "lengthMenu": "Tampilkan _MENU_ data per halaman",
                        "zeroRecords": "Data tidak ditemukan",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Tidak ada data yang tersedia",
                        "infoFiltered": "(difilter dari _MAX_ total data)",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir", 
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    },
                    pageLength: 50,
                    lengthChange: false,
                    "responsive": true,
                    "searching": true,
                    "drawCallback": function() {
                        // Re-initialize Lucide icons after each draw
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                        
                        // Style the pagination and other elements
                        $('.dataTables_length select').addClass('form-select form-select-sm');
                        $('.dataTables_length').addClass('mb-3');
                        
                        // Style pagination
                        $('.dataTables_paginate').addClass('mt-3');
                        $('.dataTables_info').addClass('mt-3');
                    }
                });

                // Custom search functionality
                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });
            }, 100);
        } catch (error) {
            console.error('DataTable initialization error:', error);
        }
    } else {
        console.log('DataTable not loaded');
    }
});
</script>

@include('components.table-styles')

<style>
#persediaanTable tbody tr{
    transition: 0.2s;
}

#persediaanTable tbody tr:hover{
    background-color: #f8f9fa;
    transform: scale(1.002);
}
</style>
</x-app-layout>
