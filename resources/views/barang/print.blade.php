<x-app-layout title="QR Code - Alur Confused" icon='<i data-lucide="qr-code" class="me-3"></i> QR Code'>

<!-- Qrious QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

<div class="container-fluid">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold text-dark mb-1">Cetak Label QR Code</h4>
            <p class="text-muted small mb-0">Pilih barang dan jumlah salinan label yang ingin Anda cetak.</p>
        </div>
    </div>

    {{-- Main Split Layout --}}
    <div class="row g-4">
        
        {{-- Left Panel: Configuration & List --}}
        <div class="col-lg-5">
            <form id="printForm" method="POST" action="{{ route('barang.downloadQrPdf') }}">
                @csrf

                <!-- Configuration Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-3 uppercase font-mono-numbers" style="font-size: 12px; letter-spacing: 0.05em;">
                            [01] Pengaturan Label
                        </h6>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Ukuran Kertas & Layout</label>
                            <select name="paper_size" id="paper_size" class="form-select minimal-select w-100">
                                <option value="a6_24">A6 - 3 Kolom (24 Label / Lembar)</option>
                                <option value="a6_12">A6 - 2 Kolom (12 Label / Lembar)</option>
                                <option value="single">Single Label (180mm x 45mm)</option>
                                <option value="thermal">Thermal Roll (58mm x 27mm)</option>
                            </select>
                            <div class="form-text text-muted" style="font-size: 11px;">Sesuaikan dengan tipe printer dan label yang Anda miliki.</div>
                        </div>
                    </div>
                </div>

                <!-- Goods Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 uppercase font-mono-numbers" style="font-size: 12px; letter-spacing: 0.05em;">
                                [02] Daftar Barang
                            </h6>
                            <button type="button"
                                class="btn btn-primary d-flex align-items-center"
                                data-bs-toggle="modal"
                                data-bs-target="#addBarangModal"
                                style="padding: 6px 12px !important; font-size: 10px !important;">
                                <i data-lucide="plus" class="me-1" style="width:14px; height:14px;"></i>
                                Tambah
                            </button>
                        </div>

                        <!-- Selected Items Table -->
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th style="width: 110px;">Kode</th>
                                        <th style="width: 80px;">Qty</th>
                                        <th style="width: 50px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="barangTableBody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4 small">
                                            <i data-lucide="inbox" class="d-block mx-auto mb-2 opacity-50" style="width: 24px; height: 24px;"></i>
                                            Belum ada barang yang dipilih
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" name="action" value="preview" class="btn btn-info flex-grow-1 text-white d-flex align-items-center justify-content-center">
                                <i data-lucide="file-search" class="me-2" style="width: 14px; height:14px;"></i> 
                                Buka PDF
                            </button>
                            <button type="submit" name="action" value="download" class="btn btn-success flex-grow-1 d-flex align-items-center justify-content-center">
                                <i data-lucide="download" class="me-2" style="width: 14px; height:14px;"></i> 
                                Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Right Panel: Modern Real-Time Preview --}}
        <div class="col-lg-7">
            <div class="card h-100 mb-0">
                <div class="card-body d-flex flex-column">
                    <h6 class="fw-bold text-dark mb-1 uppercase font-mono-numbers" style="font-size: 12px; letter-spacing: 0.05em;">
                        [03] Pratinjau Lembar Label (Real-Time)
                    </h6>
                    <p class="text-muted small mb-4">Gambaran visual label saat dicetak secara fisik.</p>

                    <!-- Preview Container Box (Compact & Aligned) -->
                    <div class="preview-paper-tray flex-grow-1 d-flex flex-column justify-content-between p-3" style="background: #f1f5f9; border-radius: 8px; border: 1px dashed var(--border-color); height: 580px; box-sizing: border-box;">
                        
                        <!-- Sliding Viewport -->
                        <div class="preview-viewport flex-grow-1 w-100 d-flex align-items-center justify-content-center" style="overflow: hidden; position: relative;">
                            <div id="previewSlideTrack" class="preview-slide-track d-flex w-100" style="transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                                
                                <!-- Placeholder Mockup -->
                                <div id="previewPlaceholder" class="text-center text-muted p-5 w-100" style="padding-top: 130px !important;">
                                    <i data-lucide="printer" class="d-block mx-auto mb-3 opacity-25" style="width: 48px; height: 48px;"></i>
                                    <span class="small">Pilih barang untuk melihat pratinjau lembar cetak</span>
                                </div>

                            </div>
                        </div>

                        <!-- Pager Controller -->
                        <div id="previewPager" class="d-flex align-items-center justify-content-center mt-2 w-100" style="display: none !important;">
                            <!-- Loaded via JS -->
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL CHOOSE GOODS --}}
<div class="modal fade" id="addBarangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow border-0" style="border-radius: 12px;">
            
            {{-- modal header --}}
            <div class="modal-header border-bottom px-4 py-3" style="background: #f8fafc; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title fw-bold text-dark" style="font-size: 15px;">
                    <i data-lucide="plus-circle" class="me-2 text-primary" style="width: 18px; height: 18px; vertical-align: -3px;"></i>
                    Pilih Barang untuk Label
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- modal body --}}
            <div class="modal-body p-4">
                <div class="position-relative mb-3">
                    <input type="text" id="searchBarang" class="form-control ps-5" placeholder="Cari berdasarkan nama atau kode barang..." style="height: 46px; border-radius: 6px;">
                    <i data-lucide="search" class="position-absolute text-muted opacity-50" style="width: 18px; height: 18px; left: 16px; top: 50%; transform: translateY(-50%);"></i>
                </div>

                <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th style="width: 140px;">Kode Barang</th>
                                <th style="width: 100px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="modalBarangTableBody">
                            <!-- Goods rows will load here -->
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- modal footer --}}
            <div class="modal-footer border-top px-4 py-3" style="background: #f8fafc; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <button type="button" class="btn btn-outline-steel px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanBarang" class="btn btn-primary px-4" data-bs-dismiss="modal">Simpan</button>
            </div>

        </div>
    </div>
</div>

{{-- Dynamic Preview Styling matching download.blade.php EXACTLY --}}
<style>
/* Slider Track structures */
.preview-viewport {
    width: 100%;
    overflow: hidden;
    position: relative;
}

.preview-slide-track {
    display: flex;
    width: 100%;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.preview-slide {
    flex-shrink: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
}

.pdf-preview-sheet {
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.1);
    box-sizing: border-box;
    font-family: 'DejaVu Sans', 'Poppins', sans-serif !important;
    color: #000000;
    transition: all 0.3s ease;
    transform-origin: top center;
    font-size: 0 !important; /* Eliminate whitespace gap between inline-block items */
    line-height: 0 !important;
}

/* A6 Sheet Mock (105mm x 148mm with 3mm margins) */
.pdf-preview-sheet.a6-page {
    width: 105mm;
    min-height: 148mm;
    padding: 3mm;
    margin: 0 auto;
    zoom: 0.95; /* Optimal Zoom to fit nicely in preview area */
}

/* Thermal Sheet Mock (58mm width) */
.pdf-preview-sheet.thermal-page {
    width: 58mm;
    min-height: 27mm;
    padding: 0;
    margin: 0 auto;
    border-left: 2px dashed #cbd5e1;
    border-right: 2px dashed #cbd5e1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    zoom: 1.6; /* Enlarged thermal sticker preview */
}

/* Grid table for preview (mirrors DomPDF table layout) */
.pdf-preview-sheet .grid-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.pdf-preview-sheet .grid-table td {
    padding: 0;
    margin: 0;
    vertical-align: top;
    text-align: center;
}

/* Exact label box styles */
.pdf-preview-sheet .label-box {
    display: inline-block;
    vertical-align: top;
    box-sizing: border-box;
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    text-align: left;
}

/* a6_24 layout (3 col x 8 row) on A6 */
.pdf-preview-sheet.layout-a6_24 .grid-table td {
    height: 17mm;
    padding: 0.3mm;
}
.pdf-preview-sheet.layout-a6_24 .label-box {
    width: 31mm;
    height: 16mm;
}

/* a6_12 layout (2 col x 6 row) on A6 */
.pdf-preview-sheet.layout-a6_12 .grid-table td {
    height: 23mm;
    padding: 0.4mm;
}
.pdf-preview-sheet.layout-a6_12 .label-box {
    width: 47mm;
    height: 22mm;
}

/* single layout (1 col x 3 row) on A6 */
.pdf-preview-sheet.layout-single .grid-table td {
    height: 47mm;
    padding: 0.5mm;
}
.pdf-preview-sheet.layout-single .label-box {
    width: 95mm;
    height: 45mm;
}

/* thermal layout */
.pdf-preview-sheet.layout-thermal .label-box {
    width: 58mm;
    height: 27mm;
    border-bottom: 1px dashed #ea580c; /* Thermal cutting line */
}

/* Shared label container */
.pdf-preview-sheet .label-container {
    width: 100%;
    height: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    box-sizing: border-box;
}

.pdf-preview-sheet .label-container td {
    padding: 0;
    margin: 0;
    vertical-align: middle;
}

/* layout-a6_24 specific styles */
.pdf-preview-sheet.layout-a6_24 .label-container {
    padding: 1mm;
}
.pdf-preview-sheet.layout-a6_24 .td-qr {
    width: 11mm;
    text-align: center;
}
.pdf-preview-sheet.layout-a6_24 .qr-img-canvas {
    width: 10mm;
    height: 10mm;
    display: block;
    margin: 0 auto;
}
.pdf-preview-sheet.layout-a6_24 .td-info {
    padding-left: 1mm;
    padding-right: 0.5mm;
    text-align: left;
}
.pdf-preview-sheet.layout-a6_24 .kode {
    font-size: 6.5pt;
    font-weight: bold;
    line-height: 1.1;
    margin-bottom: 0.5mm;
    color: #000000;
}
.pdf-preview-sheet.layout-a6_24 .nama {
    font-size: 5.5pt;
    line-height: 1.2;
    color: #000000;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* layout-a6_12 specific styles */
.pdf-preview-sheet.layout-a6_12 .label-container {
    padding: 1.5mm;
}
.pdf-preview-sheet.layout-a6_12 .td-qr {
    width: 16mm;
    text-align: center;
}
.pdf-preview-sheet.layout-a6_12 .qr-img-canvas {
    width: 15mm;
    height: 15mm;
    display: block;
    margin: 0 auto;
}
.pdf-preview-sheet.layout-a6_12 .td-info {
    padding-left: 1.5mm;
    padding-right: 1mm;
    text-align: left;
}
.pdf-preview-sheet.layout-a6_12 .kode {
    font-size: 10pt;
    font-weight: bold;
    line-height: 1.1;
    margin-bottom: 1mm;
    color: #000000;
}
.pdf-preview-sheet.layout-a6_12 .nama {
    font-size: 8pt;
    line-height: 1.2;
    color: #000000;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* layout-single specific styles */
.pdf-preview-sheet.layout-single .label-container {
    padding: 3mm;
}
.pdf-preview-sheet.layout-single .td-qr {
    width: 35mm;
    text-align: center;
}
.pdf-preview-sheet.layout-single .qr-img-canvas {
    width: 30mm;
    height: 30mm;
    display: block;
    margin: 0 auto;
}
.pdf-preview-sheet.layout-single .td-info {
    padding-left: 4mm;
    padding-right: 2mm;
    text-align: left;
}
.pdf-preview-sheet.layout-single .kode {
    font-size: 16pt;
    font-weight: bold;
    line-height: 1.1;
    margin-bottom: 2mm;
    color: #000000;
}
.pdf-preview-sheet.layout-single .nama {
    font-size: 12pt;
    line-height: 1.2;
    color: #000000;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* layout-thermal specific styles */
.pdf-preview-sheet.layout-thermal .label-container {
    padding: 1.2mm 2.8mm;
}
.pdf-preview-sheet.layout-thermal .td-qr {
    width: 20mm;
    text-align: center;
}
.pdf-preview-sheet.layout-thermal .qr-img-canvas {
    width: 18mm;
    height: 18mm;
    display: block;
    margin: 0 auto;
}
.pdf-preview-sheet.layout-thermal .td-info {
    padding-left: 2.5mm;
    padding-right: 1mm;
    text-align: left;
}
.pdf-preview-sheet.layout-thermal .kode {
    font-size: 14pt;
    font-weight: bold;
    line-height: 1.1;
    margin-bottom: 1.5mm;
    color: #000000;
}
.pdf-preview-sheet.layout-thermal .nama {
    font-size: 11pt;
    line-height: 1.2;
    color: #000000;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
</style>

<script>
let selectedBarang = [];      // final selected barang array
let tempSelectedBarang = [];  // temp selections in modal
let allBarangs = [];
let currentPage = 1;
let totalPages = 1;

document.addEventListener('DOMContentLoaded', function () {
    loadModalBarang();

    // Trigger filters on modal search input
    document.getElementById('searchBarang')
        .addEventListener('input', function () {
            filterModalBarang(this.value);
        });

    // Sync selected items to temp variable when opening modal
    document.getElementById('addBarangModal')
        .addEventListener('show.bs.modal', function () {
            tempSelectedBarang = JSON.parse(JSON.stringify(selectedBarang)); // deep clone
            loadModalBarang();
        });

    // Save selected items from modal to final array
    document.getElementById('btnSimpanBarang')
        .addEventListener('click', function () {
            selectedBarang = JSON.parse(JSON.stringify(tempSelectedBarang));
            updateTableDisplay();
        });

    // Listen for template size changes to update real-time preview instantly
    document.getElementById('paper_size')
        .addEventListener('change', function () {
            currentPage = 1; // reset to first page on layout change
            updateLivePreview();
        });

    // Form submit check
    document.getElementById('printForm')
        .addEventListener('submit', function(e){
            if(selectedBarang.length === 0){
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Daftar Kosong',
                    text: 'Silakan pilih minimal 1 barang terlebih dahulu!',
                    confirmButtonColor: '#0f172a'
                });
                return;
            }

            // Remove any dynamically created hidden inputs from previous submits
            document.querySelectorAll('.dynamic-hidden').forEach(el => el.remove());

            // Append selections as hidden inputs dynamically
            selectedBarang.forEach((item, index) => {
                let barangInput = document.createElement('input');
                barangInput.type = 'hidden';
                barangInput.name = `barang_id[${index}]`;
                barangInput.value = item.id;
                barangInput.classList.add('dynamic-hidden');
                this.appendChild(barangInput);

                let qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = `qty[${index}]`;
                qtyInput.value = item.qty;
                qtyInput.classList.add('dynamic-hidden');
                this.appendChild(qtyInput);
            });
        });
});

function loadModalBarang(){
    allBarangs = @json($barangs ?? []);
    renderModalTable(allBarangs);
}

function renderModalTable(data){
    let html = '';

    if (data.length === 0) {
        html = `<tr><td colspan="3" class="text-center text-muted py-3 small">Barang tidak ditemukan</td></tr>`;
    } else {
        data.forEach(item => {
            let selected = tempSelectedBarang.some(x => x.id === item.id);
            html += `
            <tr>
                <td class="small fw-semibold text-dark">${item.nama_barang}</td>
                <td class="small font-mono-numbers">${item.kode_barang}</td>
                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-sm py-1 px-3 ${selected ? 'btn-outline-steel' : 'btn-primary'}"
                        onclick="selectBarang(${item.id}, '${item.nama_barang.replace(/'/g, "\\'")}', '${item.kode_barang}')"
                        style="font-size: 10px !important;"
                    >
                        ${selected ? '<i data-lucide="check" class="d-inline" style="width:11px; height:11px; vertical-align:-1px;"></i> Dipilih' : 'Pilih'}
                    </button>
                </td>
            </tr>
            `;
        });
    }

    document.getElementById('modalBarangTableBody').innerHTML = html;
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function selectBarang(id, nama, kode){
    let index = tempSelectedBarang.findIndex(x => x.id === id);

    if (index === -1) {
        tempSelectedBarang.push({
            id: id,
            nama: nama,
            kode: kode,
            qty: 0
        });
    } else {
        tempSelectedBarang.splice(index, 1);
    }

    renderModalTable(allBarangs);
}

function filterModalBarang(keyword){
    keyword = keyword.toLowerCase();
    let filtered = allBarangs.filter(item =>
        item.nama_barang.toLowerCase().includes(keyword) ||
        item.kode_barang.toLowerCase().includes(keyword)
    );
    renderModalTable(filtered);
}

function updateTableDisplay(){
    let body = document.getElementById('barangTableBody');

    if(selectedBarang.length === 0){
        body.innerHTML = `
        <tr>
            <td colspan="4" class="text-center text-muted py-4 small">
                <i data-lucide="inbox" class="d-block mx-auto mb-2 opacity-50" style="width: 24px; height: 24px;"></i>
                Belum ada barang yang dipilih
            </td>
        </tr>`;
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        currentPage = 1;
        updateLivePreview();
        return;
    }

    let html = '';

    selectedBarang.forEach((item, index) => {
        html += `
        <tr>
            <td class="small fw-semibold text-dark text-truncate" style="max-width: 140px;" title="${item.nama}">${item.nama}</td>
            <td class="small font-mono-numbers">${item.kode}</td>
            <td>
                <input
                    type="number"
                    min="0"
                    class="form-control text-center py-1 font-mono-numbers qty-input"
                    value="${item.qty}"
                    style="height: 32px; font-size: 12px; width: 70px; border-radius:4px;"
                    onchange="updateQuantity(${index}, this.value)"
                    onclick="this.select()"
                >
            </td>
            <td class="text-center">
                <button
                    type="button"
                    class="btn btn-danger btn-sm p-1 d-flex align-items-center justify-content-center mx-auto"
                    style="width: 28px; height: 28px; border-radius: 4px;"
                    onclick="removeBarang(${index})"
                >
                    <i data-lucide="trash-2" style="width:14px; height:14px;"></i>
                </button>
            </td>
        </tr>
        `;
    });

    body.innerHTML = html;
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Reset page view to first page on list modification
    currentPage = 1;
    updateLivePreview();
}

function updateQuantity(index, val) {
    let quantity = parseInt(val);
    if (isNaN(quantity) || quantity < 1) quantity = 1;
    selectedBarang[index].qty = quantity;
    
    currentPage = 1; // reset page index on qty change
    updateLivePreview();
}

function removeBarang(index){
    selectedBarang.splice(index, 1);
    updateTableDisplay();
}

/* ==========================================================================
   SLIDING PREVIEW PAGER NAVIGATION
   ========================================================================== */
function changePreviewPage(direction) {
    currentPage += direction;
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;
    
    // Smooth flex translation
    const track = document.getElementById('previewSlideTrack');
    track.style.transform = `translateX(-${(currentPage - 1) * 100}%)`;
    
    // Update Text indicator & button disable states
    document.getElementById('currentPageText').innerText = currentPage;
    document.getElementById('btnPrevPage').disabled = (currentPage === 1);
    document.getElementById('btnNextPage').disabled = (currentPage === totalPages);
}

/* ==========================================================================
   REAL-TIME HTML PREVIEW RENDERER
   ========================================================================== */
function updateLivePreview() {
    const track = document.getElementById('previewSlideTrack');
    const paperSize = document.getElementById('paper_size').value;

    if (selectedBarang.length === 0) {
        track.style.transform = 'none';
        track.innerHTML = `
            <div id="previewPlaceholder" class="text-center text-muted p-5 w-100" style="padding-top: 130px !important;">
                <i data-lucide="printer" class="d-block mx-auto mb-3 opacity-25" style="width: 48px; height: 48px;"></i>
                <span class="small">Pilih barang untuk melihat pratinjau lembar cetak</span>
            </div>
        `;
        document.getElementById('previewPager').style.setProperty('display', 'none', 'important');
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        return;
    }

    // Generate flat list of labels based on quantities
    let labelsList = [];
    selectedBarang.forEach(item => {
        for(let i = 0; i < item.qty; i++) {
            labelsList.push(item);
        }
    });

    // Define labels per page and columns per row depending on template
    let labelsPerPage = 24;
    let cols = 3;
    if (paperSize === 'a6_24') { labelsPerPage = 24; cols = 3; }
    else if (paperSize === 'a6_12') { labelsPerPage = 12; cols = 2; }
    else if (paperSize === 'single') { labelsPerPage = 3; cols = 1; }
    else if (paperSize === 'thermal') { labelsPerPage = 1; cols = 1; }

    totalPages = Math.ceil(labelsList.length / labelsPerPage);
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    let html = '';
    
    // Render each slide page
    for (let p = 0; p < totalPages; p++) {
        let pageClass = paperSize === 'thermal' ? 'pdf-preview-sheet thermal-page layout-thermal' : `pdf-preview-sheet a6-page layout-${paperSize}`;
        
        html += `<div class="preview-slide">`;
        html += `<div class="${pageClass}">`;
        
        // Labels for this page
        let startIdx = p * labelsPerPage;
        let endIdx = Math.min(startIdx + labelsPerPage, labelsList.length);
        let pageLabels = labelsList.slice(startIdx, endIdx);
        
        if (paperSize === 'thermal') {
            // Thermal: single label, no grid table
            const item = pageLabels[0];
            const uniqueId = `qr_canvas_${startIdx}`;
            html += `
                <div class="label-box">
                    <table class="label-container">
                        <tr>
                            <td class="td-qr">
                                <canvas class="qr-img-canvas" id="${uniqueId}"></canvas>
                            </td>
                            <td class="td-info">
                                <div class="kode">${item.kode}</div>
                                <div class="nama">${item.nama}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            `;
        } else {
            // A6 layouts: use table grid for perfect centering
            html += `<table class="grid-table">`;
            
            // Split page labels into rows
            for (let r = 0; r < pageLabels.length; r += cols) {
                html += `<tr>`;
                for (let c = 0; c < cols; c++) {
                    let idx = r + c;
                    if (idx < pageLabels.length) {
                        const item = pageLabels[idx];
                        const uniqueId = `qr_canvas_${startIdx + idx}`;
                        html += `
                            <td>
                                <div class="label-box">
                                    <table class="label-container">
                                        <tr>
                                            <td class="td-qr">
                                                <canvas class="qr-img-canvas" id="${uniqueId}"></canvas>
                                            </td>
                                            <td class="td-info">
                                                <div class="kode">${item.kode}</div>
                                                <div class="nama">${item.nama}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        `;
                    } else {
                        html += `<td></td>`;
                    }
                }
                html += `</tr>`;
            }
            
            html += `</table>`;
        }
        
        html += `</div>`; // end pdf-preview-sheet
        html += `</div>`; // end preview-slide
    }

    track.innerHTML = html;
    
    // Slide track to show current page
    track.style.transform = `translateX(-${(currentPage - 1) * 100}%)`;

    // Render Pager Navigation if totalPages > 1
    const pager = document.getElementById('previewPager');
    if (totalPages > 1) {
        let pagerHtml = `
            <button type="button" class="btn btn-outline-steel btn-sm px-2 py-1" onclick="changePreviewPage(-1)" id="btnPrevPage" style="border-radius: 4px; padding: 4px 8px !important;">
                <i data-lucide="chevron-left" style="width: 16px; height: 16px;"></i>
            </button>
            <span class="font-mono-numbers small fw-bold text-secondary mx-3" style="font-size: 11px;">
                Halaman <span id="currentPageText">${currentPage}</span> dari <span id="totalPagesText">${totalPages}</span>
            </span>
            <button type="button" class="btn btn-outline-steel btn-sm px-2 py-1" onclick="changePreviewPage(1)" id="btnNextPage" style="border-radius: 4px; padding: 4px 8px !important;">
                <i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i>
            </button>
        `;
        pager.innerHTML = pagerHtml;
        pager.style.setProperty('display', 'flex', 'important');
        
        document.getElementById('btnPrevPage').disabled = (currentPage === 1);
        document.getElementById('btnNextPage').disabled = (currentPage === totalPages);
    } else {
        pager.style.setProperty('display', 'none', 'important');
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Draw high quality real-time QR Codes on the generated canvases using QRious
    for (let i = 0; i < labelsList.length; i++) {
        const item = labelsList[i];
        const canvasId = `qr_canvas_${i}`;
        const canvasEl = document.getElementById(canvasId);
        
        if (canvasEl) {
            try {
                new QRious({
                    element: canvasEl,
                    value: item.kode,
                    size: 100, // exact digital fit for 18mm rendering
                    level: 'M',
                    background: '#ffffff',
                    foreground: '#000000'
                });
            } catch (e) {
                console.error('Error rendering QR Code:', e);
            }
        }
    }
}

document.addEventListener('keydown', function(e) {
    if (!e.target.classList.contains('qty-input')) return;

    if (e.key === 'Enter') {
        e.preventDefault();

        const inputs = [...document.querySelectorAll('.qty-input')];
        const index = inputs.indexOf(e.target);

        if (index < inputs.length - 1) {
            inputs[index + 1].focus();
            inputs[index + 1].select();
        }
    }
});
</script>
</x-app-layout>