<x-app-layout title="Tambah Toko - Alur Confused" icon='<i data-lucide="package-plus" class="me-3"></i> Tambah Toko'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Tambah Toko</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Tambah data toko baru</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('toko.index') }}" class="btn btn-outline-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">
                <form id="tokoForm" action="{{ route('toko.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">

                        <!-- Nama Toko -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control minimal-input @error('toko') is-invalid @enderror"
                                    id="nama_toko"
                                    name="nama_toko"
                                    value="{{ old('nama_toko') }}"
                                    placeholder=""
                                    required
                                >

                                <label for="nama_toko">
                                    Nama Toko *
                                </label>

                                @error('nama_toko')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- platform -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control minimal-input @error('platform') is-invalid @enderror"
                                    id="platform"
                                    name="platform"
                                    value="{{ old('platform') }}"
                                    placeholder=""
                                >

                                <label for="platform">
                                    Platform (Opsional)
                                </label>

                                @error('platform')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Barang Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Daftar Barang</h6>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBarangModal">
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Barang
                                    </p>
                                </button>
                            </div>

                            <!-- Barang Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle" id="barangTable">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Nama Barang</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Harga Barang</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Satuan</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); display: none;" id="actionColumn">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barangTableBody">
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada barang yang dipilih</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Submit Buttons -->
<div class="d-flex justify-content-end gap-3 mt-5">
    <a href="{{ route('toko.index') }}" class="btn btn-light minimal-btn-secondary">
        <p class="d-flex align-items-center mb-0">
            <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
        </p>
    </a>
    <button 
        type="submit"
        form="tokoForm"
        class="btn btn-primary minimal-btn-primary">
            <p class="d-flex align-items-center mb-0">
                <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Toko
            </p>
    </button>
</div>

<!-- Add Barang Modal -->
<div class="modal fade" id="addBarangModal" tabindex="-1" aria-labelledby="addBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBarangModalLabel">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Bar -->
                <div class="search-container mb-3">
                    <div class="search-input">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" id="searchBarang" class="form-control" placeholder="Cari barang...">
                    </div>
                </div>

                <!-- Barang List -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="modalBarangTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="modalBarangTableBody">
                            <!-- Dynamic content -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="pagination-info">
                        <small class="text-muted">Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalItems">0</span> item</small>
                    </div>
                    <nav aria-label="Pagination">
                        <ul class="pagination pagination-sm mb-0" id="paginationControls">
                            <!-- Pagination buttons will be generated by JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    id="btnSimpanBarang"
                    class="btn btn-primary"
                    data-bs-dismiss="modal">
                    Simpan Barang
                </button>
            </div>

        </div>
    </div>
</div>

    <style>
        .table-transparent {
            background-color: transparent !important;
        }
        
        .table-transparent th,
        .table-transparent td {
            background-color: transparent !important;
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .table-transparent tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            width: 20px;
            height: 20px;
            color: #6c757d;
            z-index: 1;
        }

        .search-input input {
            padding-left: 44px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .search-input input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Pagination Styling */
        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.2rem;
        }

        .pagination .page-link {
            color: #4AC8EA;
            border-color: #dee2e6;
        }

        .pagination .page-link:hover {
            color: #39b8d6;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #4AC8EA;
            border-color: #4AC8EA;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination-info {
            font-size: 0.875rem;
        }

        /* Minimal Form Styling */
        .minimal-input {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .minimal-input:focus {
            border-color: #4AC8EA;
            box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
            background: #ffffff;
        }

        .form-floating > .minimal-input {
            padding: 1.625rem 1rem 0.625rem;
        }

        .form-floating > label {
            padding: 1rem;
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .minimal-btn-primary {
            background: linear-gradient(135deg, #4AC8EA 0%, #4AC8EA 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .minimal-btn-primary:hover {
            background: linear-gradient(135deg, #39b8d6 0%, #39b8d6 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 200, 234, 0.3);
        }

        .minimal-btn-secondary {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            font-size: 14px;
            color: #6c757d;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .minimal-btn-secondary:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #4AC8EA;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .form-control,
        .form-control:focus {
        background-color: white !important;
        }

        .table th {
            font-weight: 600;
            font-size: 14px;
        }

        .table td {
            vertical-align: middle;
        }

        /* Modal jangan terlalu tinggi */
        #addBarangModal .modal-dialog {
            max-width: 800px;
            margin-top: 30px;
        }

        /* Batasi tinggi modal */
        #addBarangModal .modal-content {
            max-height: 85vh;
        }

        /* Isi modal bisa discroll */
        #addBarangModal .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }

        /* Footer tetap kelihatan */
        #addBarangModal .modal-footer {
            position: sticky;
            bottom: 0;
            background: white;
            border-top: 1px solid #eee;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize
            updateTableDisplay();
            loadModalBarang();

            // Search barang
            document.getElementById('searchBarang').addEventListener('input', function() {
                filterModalBarang(this.value);
            });

            // Reload isi modal setiap dibuka
            document.getElementById('addBarangModal')
                .addEventListener('show.bs.modal', function() {
                    loadModalBarang();
                });

            document.getElementById('btnSimpanBarang').addEventListener('click', function () {
                updateTableDisplay();
            });

            // Form submit
            document.getElementById('tokoForm').addEventListener('submit', function(e) {
                if (selectedBarang.length === 0) {
                    e.preventDefault();
                    alert('Minimal pilih satu barang!');
                    return false;
                }

                // Hapus hidden input lama
                document.querySelectorAll('.dynamic-hidden').forEach(el => el.remove());

                // Tambahkan hidden input baru
                selectedBarang.forEach((item, index) => {
                    const barangIdInput = document.createElement('input');
                    barangIdInput.type = 'hidden';
                    barangIdInput.name = `barang_id[${index}]`;
                    barangIdInput.value = item.id;
                    barangIdInput.classList.add('dynamic-hidden');
                    this.appendChild(barangIdInput);

                    const hargaInput = document.createElement('input');
                    hargaInput.type = 'hidden';
                    hargaInput.name = `harga[${index}]`;
                    hargaInput.value = item.harga;
                    hargaInput.classList.add('dynamic-hidden');
                    this.appendChild(hargaInput);
                });
            });
        });

let selectedBarang = [];
let originalBarang = []; // Store original data for cancel functionality
let allBarangs = []; // Store all barang data
let filteredBarangs = []; // Store filtered barang data
let currentPage = 1;
const itemsPerPage = 10; // Show 10 items per page

function updateTableDisplay() {
    const tableBody = document.getElementById('barangTableBody');
    const actionColumn = document.getElementById('actionColumn');
    
    if (selectedBarang.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Belum ada barang yang dipilih</td></tr>';
        if (actionColumn) actionColumn.style.display = 'table-cell';
    } else {
        let html = '';
        selectedBarang.forEach((item, index) => {
            html += `
                <tr>
                    <td>${item.nama}</td>
                    <td>
                        <input
                            type="number"
                            inputmode="numeric"
                            class="form-control form-control-sm"
                            value="${item.harga}"
                            onchange="updateHarga(${index}, this.value)"
                            placeholder="Masukkan harga"
                        >
                    </td>

                    <td>${item.satuan}</td>

                    <td>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            onclick="removeBarang(${index})"
                        >
                            🗑
                        </button>
                    </td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
        
        // Show/hide action column based on edit mode
        if (actionColumn) actionColumn.style.display = 'table-cell';
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
}

function loadModalBarang() {
    // Use barangs data from backend
    allBarangs = @json($barangs ?? []);
    filteredBarangs = [...allBarangs]; // Initially show all items
    currentPage = 1; // Reset to first page
    displayModalBarang();
}

function displayModalBarang() {
    const tableBody = document.getElementById('modalBarangTableBody');
    
    // Calculate pagination
    const totalItems = filteredBarangs.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
    const currentItems = filteredBarangs.slice(startIndex, endIndex);
    
    // Update table content
let html = '';

if (currentItems.length === 0) {
    html = '<tr><td colspan="4" class="text-center text-muted">Tidak ada barang ditemukan</td></tr>';
    } else {
        currentItems.forEach(item => {
            const isSelected = selectedBarang.some(
                selected => selected.id === item.id
            );

            html += `
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>${item.satuan || 'Pcs'}</td>
                    <td>
                        <button 
                            type="button"
                            class="btn btn-primary btn-sm"
                            onclick="selectBarang(
                                ${item.id},
                                '${item.nama_barang}',
                                '${item.satuan || 'Pcs'}'
                            )"
                            ${isSelected ? 'disabled' : ''}
                        >
                            ${isSelected ? 'Dipilih' : 'Pilih'}
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
        tableBody.innerHTML = html;
        
        // Update pagination info
        updatePaginationInfo(startIndex + 1, endIndex, totalItems);
        
        // Update pagination controls
        updatePaginationControls(currentPage, totalPages);
    }

    function updatePaginationInfo(start, end, total) {
        document.getElementById('showingStart').textContent = total > 0 ? start : 0;
        document.getElementById('showingEnd').textContent = end;
        document.getElementById('totalItems').textContent = total;
    }

    function updatePaginationControls(current, total) {
        const paginationControls = document.getElementById('paginationControls');
        let html = '';
        
        // Previous button
        html += `
            <li class="page-item ${current === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${current - 1}); return false;" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;
        
        // Page numbers
        const maxVisible = 5;
        let startPage = Math.max(1, current - Math.floor(maxVisible / 2));
        let endPage = Math.min(total, startPage + maxVisible - 1);
        
        // Adjust startPage if we're near the end
        if (endPage - startPage + 1 < maxVisible) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        // First page and ellipsis
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            html += `
                <li class="page-item ${i === current ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `;
        }
        
        // Last page and ellipsis
        if (endPage < total) {
            if (endPage < total - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${total}); return false;">${total}</a></li>`;
        }
        
        // Next button
        html += `
            <li class="page-item ${current === total ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${current + 1}); return false;" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;
        
        paginationControls.innerHTML = html;
    }

    function changePage(page) {
        const totalPages = Math.ceil(filteredBarangs.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            displayModalBarang();
        }
    }

    function filterModalBarang(searchTerm) {
        // Filter barang based on search term
        if (searchTerm.trim() === '') {
            filteredBarangs = [...allBarangs];
        } else {
            filteredBarangs = allBarangs.filter(item => {
                const searchFields = [
                    item.nama_barang?.toLowerCase() || '',
                    item.warna?.toLowerCase() || '',
                    item.satuan?.toLowerCase() || ''
                ];
                return searchFields.some(field => field.includes(searchTerm.toLowerCase()));
            });
        }
        
        // Reset to first page when filtering
        currentPage = 1;
        displayModalBarang();
    }

    function selectBarang(id, nama, satuan) {
        const alreadySelected = selectedBarang.some(
            item => item.id === id
        );

        if (!alreadySelected) {
            selectedBarang.push({
                id: id,
                nama: nama,
                satuan: satuan,
                harga: ''
            });
        }

        displayModalBarang();
    }

    function removeBarang(index) {
        if (confirm('Yakin ingin menghapus barang ini?')) {
            if (index >= 0 && index < selectedBarang.length) {
                selectedBarang.splice(index, 1);
                updateTableDisplay();
                displayModalBarang(); // Refresh modal to update button states with pagination
            }
        }
    }

    function updateHarga(index, newHarga) {
        selectedBarang[index].harga = newHarga;
    }

        document.addEventListener('keydown', function(e) {
        // cuma berlaku untuk input harga barang
        if (
            e.key === 'Enter' &&
            e.target.matches('#barangTableBody input[type="number"]')
        ) {
            e.preventDefault();

            const hargaInputs = Array.from(
                document.querySelectorAll(
                    '#barangTableBody input[type="number"]'
                )
            );

            const index = hargaInputs.indexOf(e.target);

            if (index > -1 && index < hargaInputs.length - 1) {
                hargaInputs[index + 1].focus();
            }
        }
    });

    </script>
</x-app-layout>
