<x-app-layout title="Edit Barang Masuk - Alur Confused" icon='<i data-lucide="package-plus" class="me-3"></i> Edit Barang Masuk'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Edit Barang Masuk</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Edit data barang masuk baru</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('barang_masuk.index') }}" class="btn btn-outline-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any() || session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border: 1px solid rgba(220, 53, 69, 0.15); background-color: rgba(220, 53, 69, 0.03); color: #e15b64; font-size: 13px;">
                <div class="d-flex align-items-center">
                    <i data-lucide="alert-circle" class="me-2" style="width: 18px; height: 18px;"></i>
                    <strong class="me-1">Terjadi kesalahan:</strong>
                    <span>{{ session('error') ?? 'Silakan periksa kembali inputan Anda.' }}</span>
                </div>
                @if($errors->any())
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">
                <form id="barangMasukForm" action="{{ route('barang_masuk.update', $barangMasuk->id) }}" method="POST">
                @csrf
                @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- No Barang Masuk -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control minimal-input" id="no_barang_masuk" 
                                       value="{{ $noBarangMasuk }}" readonly>
                                <label for="no_barang_masuk">No Barang Masuk</label>
                            </div>

                            <!-- Tanggal Masuk -->
                            <div class="form-floating mb-3">
                                <input type="date" 
                                       class="form-control minimal-input @error('tanggal_masuk') is-invalid @enderror" 
                                       id="tanggal_masuk" 
                                       name="tanggal_masuk" 
                                       value="{{ old('tanggal_masuk', \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('Y-m-d')) }}"
                                       required>
                                <label for="tanggal_masuk">Tanggal Masuk *</label>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <input
                                type="hidden"
                                id="supplier_id"
                                name="supplier_id"
                                value="{{ $barangMasuk->supplier_id }}"
                                >

                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    class="form-control minimal-input"
                                    id="supplier_display"
                                    value="{{ $barangMasuk->supplier->nama_supplier }}"
                                    readonly
                                >
                                <label for="supplier_display">Supplier *</label>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Keterangan -->
                            <div class="form-floating">
                                <textarea class="form-control minimal-input @error('keterangan') is-invalid @enderror" 
                                          id="keterangan" 
                                          name="keterangan" 
                                          style="height: 140px"
                                          placeholder="Keterangan">{{ old('keterangan', $barangMasuk->keterangan) }}</textarea>
                                <label for="keterangan">Keterangan</label>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                            <th style="font-weight: 600; width: 30%; color: var(--color-foreground);">Nama Barang</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Harga</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Stok</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Satuan</th>
                                            <th style="font-weight: 600; width: 120px; color: var(--color-foreground);">Stok Masuk</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Jumlah</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); display: none;" id="actionColumn">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barangTableBody">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada barang yang dipilih</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>  
            </div>
        </div>
        <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('barang_masuk.index') }}" class="btn btn-light minimal-btn-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                    </p>
                </a>
                <button
                    type="submit"
                    form="barangMasukForm"
                    class="btn btn-primary minimal-btn-primary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Update Barang Masuk
                    </p>
                </button>
            </div>  
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
                    <table class="table table-hover" id="modalBarangTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
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

        .table th {
            font-weight: 600;
            font-size: 14px;
        }

        .table td {
            vertical-align: middle;
        }

        .qty-input{
            width:80px;
            margin:auto;
        }

        #barangTable {
            border-collapse: collapse;
            border: 1px solid #dee2e6;
        }

        #barangTable th,
        #barangTable td {
            border: 1px solid #dee2e6 !important;
            padding: 12px;
        }

        #barangTable thead th {
            background-color: #f1f3f5;
        }

        #barangTable tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        #barangTable tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    updateTableDisplay();
    loadModalBarang();

    // Search
    document.getElementById('searchBarang')
        .addEventListener('input', function() {
            filterModalBarang(this.value);
        });

    // Kalau supplier diganti
    const supplierField = document.getElementById('supplier_id');

    if (supplierField) {
        supplierField.addEventListener('change', function () {
            selectedBarang = [];
            updateTableDisplay();
            loadModalBarang();
        });
    }

    // Submit
    document.getElementById('barangMasukForm')
        .addEventListener('submit', function(e) {
            if (selectedBarang.length === 0) {
                e.preventDefault();
                alert('Minimal pilih satu barang!');
                return false;
            }
        });

    // Tombol simpan barang dari modal
    document.getElementById('btnSimpanBarang')
        .addEventListener('click', function () {
            updateTableDisplay();
        });
});

let selectedBarang = {!! json_encode(
    $barangMasuk->details->map(function($detail){
        return [
            'id' => $detail->barang->id,
            'nama' => $detail->barang->nama_barang,
            'stock' => $detail->barang->persediaan->stock ?? 0,
            'satuan' => $detail->barang->satuan,
            'harga' => $detail->harga,
            'stock_masuk' => $detail->qty,
        ];
    })->values()
) !!};
let originalBarang = []; // Store original data for cancel functionality
let allBarangs = []; // Store all barang data
let filteredBarangs = []; // Store filtered barang data
let currentPage = 1;
const itemsPerPage = 10; // Show 10 items per page


function loadModalBarang() {
    const supplierId =
        document.getElementById('supplier_id').value;

    const semuaBarang = @json($barangs ?? []);

    if (!supplierId) {
        filteredBarangs = [];
    } else {
        filteredBarangs = semuaBarang.filter(barang =>
            barang.supplier_prices[supplierId]
        );
    }

    allBarangs = filteredBarangs;
    currentPage = 1;
    displayModalBarang();
}

function displayModalBarang() {
    const tbody = document.getElementById('modalBarangTableBody');

    if (filteredBarangs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Tidak ada barang
                </td>
            </tr>
        `;
        updatePaginationInfo(0, 0, 0);
        updatePaginationControls(1, 1);
        return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageItems = filteredBarangs.slice(start, end);

    let html = '';

    pageItems.forEach(item => {
        const alreadySelected = selectedBarang.some(
            selected => selected.id === Number(item.id)
        );

        html += `
            <tr>
                <td>${item.nama_barang}</td>

                <td>
                    Rp ${Number(
                        item.supplier_prices[
                            document.getElementById('supplier_id').value
                        ] || 0
                    ).toLocaleString('id-ID')}
                </td>

                <td>${item.persediaan ? item.persediaan.stock : 0}</td>

                <td>${item.satuan}</td>

                <td>
                    ${
                        alreadySelected
                        ? `<button class="btn btn-secondary btn-sm" disabled>Dipilih</button>`
                        : `
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                onclick="selectBarang(
                                    '${item.id}',
                                    '${item.nama_barang}',
                                    '${item.persediaan ? item.persediaan.stock : 0}',
                                    '${item.satuan}',
                                    '${
                                        item.supplier_prices[
                                            document.getElementById('supplier_id').value
                                        ] || 0
                                    }'
                                )">
                                Pilih
                            </button>
                        `
                    }
                </td>
            </tr>
            `;
        });

    tbody.innerHTML = html;

    updatePaginationInfo(
        start + 1,
        Math.min(end, filteredBarangs.length),
        filteredBarangs.length
    );

    updatePaginationControls(
        currentPage,
        Math.ceil(filteredBarangs.length / itemsPerPage)
    );
}

function updateTableDisplay() {
    console.log('selectedBarang:', selectedBarang);
    const tableBody = document.getElementById('barangTableBody');
    const actionColumn = document.getElementById('actionColumn');

    tableBody.innerHTML = '';

    if (selectedBarang.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted">
                    Belum ada barang yang dipilih
                </td>
            </tr>
        `;
        actionColumn.style.display = 'none';
        return;
    }

    actionColumn.style.display = 'table-cell';

    selectedBarang.forEach((item, index) => {
        const row = document.createElement('tr');

        row.innerHTML = `
        <td>${item.nama}</td>

        <td>
            Rp ${Number(item.harga).toLocaleString('id-ID')}
        </td>

        <td>${item.stock}</td>

        <td>${item.satuan}</td>

        <td>
            <input
                type="number"
                class="form-control form-control-sm text-center qty-input"
                name="qty[]"
                min="1"
                placeholder="0"
                value="${item.stock_masuk || ''}"
                oninput="updateStock(${index}, this.value)"
                onkeydown="handleQtyEnter(event, ${index})"
                required
            >

            <input
                type="hidden"
                name="barang_id[]"
                value="${item.id}">

            <input
                type="hidden"
                name="harga[]"
                value="${item.harga}">
        </td>

        <td id="jumlah-${index}">
            Rp ${(item.stock_masuk * item.harga || 0).toLocaleString('id-ID')}
        </td>

        <td>
            <button
                type="button"
                class="btn btn-danger btn-sm"
                onclick="removeBarang(${index})">
                <i data-lucide="trash-2"
                style="width:16px;height:16px;"></i>
            </button>
        </td>
    `;

        tableBody.appendChild(row);
    });

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
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

function selectBarang(id, nama, stock, satuan, harga) {
    id = Number(id);

    if (selectedBarang.find(item => item.id === id)) {
        alert('Barang sudah dipilih!');
        return;
    }

    selectedBarang.push({
        id: id,
        nama: nama,
        stock: stock,
        satuan: satuan,
        harga: harga,
        stock_masuk: ''
    });

    displayModalBarang();
    updateTableDisplay();
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

function updateStock(index, value) {
    selectedBarang[index].stock_masuk = value;

    // update jumlah saja, tanpa render ulang tabel
    const jumlahCell = document.getElementById(`jumlah-${index}`);
    if (jumlahCell) {
        jumlahCell.innerText =
            'Rp ' + (value * selectedBarang[index].harga || 0)
                .toLocaleString('id-ID');
    }
}

function handleQtyEnter(event, index) {
    if (event.key === 'Enter') {
        event.preventDefault();

        const qtyInputs = document.querySelectorAll('.qty-input');

        if (index + 1 < qtyInputs.length) {
            qtyInputs[index + 1].focus();
            qtyInputs[index + 1].select();
        } else {
            // kalau sudah input terakhir, keluar dari field
            qtyInputs[index].blur();
        }
    }
}

    </script>
</x-app-layout>
