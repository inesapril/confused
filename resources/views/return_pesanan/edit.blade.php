<x-app-layout title="Edit Barang Retur - Alur Confused" icon='<i data-lucide="package-plus" class="me-3"></i> Edit Barang Retur'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Edit Barang Retur</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Edit data barang retur baru</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">
                <form id="returnPesananForm" action="{{ route('return_pesanan.update', $returnPesanan->id) }}" method="POST">
                @csrf
                @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- No Return Pesanan -->
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    class="form-control minimal-input"
                                    id="no_return"
                                    name="no_return"
                                    value="{{ $returnPesanan->no_return }}"
                                    readonly
                                >
                                <label for="no_return">
                                    No Return Pesanan
                                </label>
                            </div>

                            <!-- Tanggal Return -->
                            <div class="form-floating mb-3">
                                <input type="date" 
                                       class="form-control minimal-input @error('tanggal_return') is-invalid @enderror" 
                                       id="tanggal_return" 
                                       name="tanggal_return" 
                                       value="{{ old('tanggal_return', $returnPesanan->tanggal_return->format('Y-m-d')) }}"
                                       required>
                                <label for="tanggal_return">Tanggal Return *</label>
                                @error('tanggal_return')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    class="form-control minimal-input"
                                    name="no_pesanan"
                                    value="{{ old('no_pesanan', $returnPesanan->no_pesanan) }}"
                                    required
                                >
                                <label>No Pesanan *</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select id="toko" class="form-select minimal-input" name="toko" required>
                                    <option value="">Pilih toko</option>
                                    <option value="confused"
                                        {{ old('toko', $returnPesanan->toko) == 'confused' ? 'selected' : '' }}>
                                        Confused
                                    </option>
                                    <option value="wesker"
                                        {{ old('toko', $returnPesanan->toko) == 'wesker' ? 'selected' : '' }}>
                                        Wesker
                                    </option>
                                    <option value="manmayer"
                                        {{ old('toko', $returnPesanan->toko) == 'manmayer' ? 'selected' : '' }}>
                                        Manmayer
                                    </option>
                                    <option value="simplejoy"
                                        {{ old('toko', $returnPesanan->toko) == 'simplejoy' ? 'selected' : '' }}>
                                        Simplejoy
                                    </option>
                                </select>

                                <label for="toko">Toko *</label>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">

                            <div class="form-floating mb-3">
                                <select class="form-select minimal-input" name="status" required>
                                    <option value="belum_selesai"
                                        {{ old('status', $returnPesanan->status) == 'belum_selesai' ? 'selected' : '' }}>
                                        Belum selesai
                                    </option>

                                    <option value="selesai"
                                        {{ old('status', $returnPesanan->status) == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>

                                <label>Status *</label>
                            </div>
                            <!-- Keterangan -->
                            <div class="form-floating">
                                <textarea
                                    class="form-control minimal-input @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    style="height: 140px"
                                    placeholder="Keterangan"
                                >{{ old('keterangan', $returnPesanan->keterangan) }}</textarea>
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
                                            <th style="font-weight: 600; color: var(--color-foreground);">Stok</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Satuan</th>
                                            <th style="font-weight: 600; width: 120px; color: var(--color-foreground);">Qty</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">kondisi</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Total</th>
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
                    <div class="d-flex justify-content-end mt-3">
                        <div class="card p-3" style="min-width: 250px;">
                            <div class="d-flex justify-content-between">
                                <strong>Total Retur:</strong>
                                <strong id="grandTotal">Rp 0</strong>
                            </div>
                        </div>
                    </div>
                </form>  
            </div>
        </div>
        <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('return_pesanan.index') }}" class="btn btn-light minimal-btn-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                    </p>
                </a>
                <button
                    type="submit"
                    form="returnPesananForm"
                    class="btn btn-primary minimal-btn-primary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Barang
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
                    <table class="table table-striped table-hover align-middle" id="modalBarangTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
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
    updateGrandTotal();

    // Search
    document.getElementById('searchBarang')
        .addEventListener('input', function() {
            filterModalBarang(this.value);
        });

    // Submit
    document.getElementById('returnPesananForm')
        .addEventListener('submit', function(e) {
            if (!selectedBarang.length) {
                e.preventDefault();
                return;
            }
        });

    // Tombol simpan barang dari modal
    document.getElementById('btnSimpanBarang')
        .addEventListener('click', function () {
            updateTableDisplay();
        });
});

let selectedBarang = {!! json_encode(
    $returnPesanan->details->map(function($item){
        return [
            'id' => $item->barang_id,
            'nama' => $item->barang->nama_barang,
            'stock' => $item->barang->persediaan->stock ?? 0,
            'satuan' => $item->barang->satuan,
            'harga' => $item->barang->harga ?? 0, 
            'qty' => $item->qty,
            'kondisi' => $item->kondisi,
        ];
    })->values()
) !!};
let originalBarang = []; // Store original data for cancel functionality
let allBarangs = []; // Store all barang data
let filteredBarangs = []; // Store filtered barang data
let currentPage = 1;
const itemsPerPage = 10; // Show 10 items per page


function loadModalBarang() {
    const semuaBarang = @json($barangs ?? []);

    filteredBarangs = semuaBarang;

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
                                    '${item.harga || 0}'
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

            <td>${item.stock}</td>

            <td>${item.satuan}</td>

            <td>
                <input
                    type="number"
                    class="form-control form-control-sm text-center qty-input"
                    name="qty[]"
                    min="1"
                    value="${item.qty || ''}"
                    onchange="updateQty(${index}, this.value)"
                    required
                >

                <input type="hidden" name="barang_id[]" value="${item.id}">
            </td>

            <td>
                <select name="kondisi[]" class="form-select"
                onchange="updateKondisi(${index}, this.value)"
                required>
                    <option value="">Pilih</option>
                    <option value="baik" ${item.kondisi === 'baik' ? 'selected' : ''}>Baik</option>
                    <option value="rusak" ${item.kondisi === 'rusak' ? 'selected' : ''}>Rusak</option>
                    <option value="tidak_sesuai" ${item.kondisi === 'tidak_sesuai' ? 'selected' : ''}>Tidak Sesuai</option>
                </select>
            </td>

            <td>
                Rp ${((Number(item.qty) || 0) * (Number(item.harga) || 0)).toLocaleString('id-ID')}
            </td>

            <td>
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeBarang(${index})">
                    Hapus
                </button>
            </td>
        `;

        tableBody.appendChild(row);
    });

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    updateGrandTotal();
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
        id,
        nama,
        stock,
        satuan,
        harga: Number(harga) || 0,
        qty: '',
        kondisi: ''
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

function updateQty(index, value) {
    selectedBarang[index].qty = Number(value) || 0;
    updateGrandTotal();
}

function handleQtyEnter(event, index) {
    if (event.key === 'Enter') {
        event.preventDefault();

        const qtyInputs =
            document.querySelectorAll('.qty-input');

        if (index + 1 < qtyInputs.length) {
            qtyInputs[index + 1].focus();
            qtyInputs[index + 1].select();
        } else {
            qtyInputs[index].blur();
        }
    }
}

function calculateGrandTotal() {
    return selectedBarang.reduce((sum, item) => {
        return sum + ((Number(item.qty) || 0) * (Number(item.harga) || 0));
    }, 0);
}

function updateGrandTotal() {
    const total = calculateGrandTotal();

    document.getElementById('grandTotal').textContent =
        'Rp ' + total.toLocaleString('id-ID');
}

function updateKondisi(index, value) {
    selectedBarang[index].kondisi = value;
}

    </script>
</x-app-layout>
