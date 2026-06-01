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

        <!-- Header Action -->
        <div class="card border-0 shadow-sm mb-4"
            style="
                background: var(--color-background);
                border-radius: 18px;
            ">

            <div class="card-body p-4">

                <!-- Top -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                    <!-- Tambah Barang -->
                    <a href="{{ route('barang.create') }}"
                        class="btn px-4 py-2"
                        style="
                            background: var(--color-foreground);
                            color: var(--color-background);
                            border: none;
                            border-radius: 12px;
                            font-weight: 600;
                            min-width: 180px;
                            height: 48px;
                        ">

                        <div class="d-flex align-items-center justify-content-center">
                            <i data-lucide="plus"
                                class="me-2"
                                style="width: 18px; height: 18px;">
                            </i>

                            Tambah Barang
                        </div>

                    </a>

                    <!-- Search -->
                    <div style="width: 320px; max-width: 100%;">

                        <div class="position-relative">

                            <input type="text"
                                id="customSearch"
                                class="form-control ps-5"
                                placeholder="Cari barang..."
                                style="
                                    height: 48px;
                                    border-radius: 14px;
                                ">

                            <i data-lucide="search"
                                class="position-absolute"
                                style="
                                    width: 18px;
                                    height: 18px;
                                    left: 18px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    opacity: .5;
                                ">
                            </i>

                        </div>

                    </div>

                </div>

                <!-- Bottom -->
                <div class="d-flex flex-wrap gap-2 align-items-center">

                    <!-- Export -->
                    <a href="{{ route('barang.export') }}"
                        class="btn px-3 py-2"
                        style="
                            background: rgba(255,255,255,0.04);
                            color: var(--color-foreground);
                            border: 1px solid rgba(255,255,255,0.08);
                            border-radius: 10px;
                            height: 44px;
                        ">

                        <div class="d-flex align-items-center">
                            <i data-lucide="download"
                                class="me-2"
                                style="width: 16px; height: 16px;">
                            </i>

                            Export Excel
                        </div>

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
                            class="form-control"
                            style="
                                max-width: 220px;
                                height: 44px;
                                border-radius: 10px;
                            ">

                        <button type="submit"
                            class="btn px-3 py-2"
                            style="
                                background: rgba(255,255,255,0.04);
                                color: var(--color-foreground);
                                border: 1px solid rgba(255,255,255,0.08);
                                border-radius: 10px;
                                height: 44px;
                            ">

                            <div class="d-flex align-items-center">
                                <i data-lucide="upload"
                                    class="me-2"
                                    style="width: 16px; height: 16px;">
                                </i>

                                Import Excel
                            </div>

                        </button>

                    </form>

                    <!-- QR -->
                    <a href="{{ route('barang.label.index') }}"
                        class="btn px-3 py-2"
                        style="
                            background: rgba(255,255,255,0.04);
                            color: var(--color-foreground);
                            border: 1px solid rgba(255,255,255,0.08);
                            border-radius: 10px;
                            height: 44px;
                        ">

                        <div class="d-flex align-items-center">
                            <i data-lucide="qr-code"
                                class="me-2"
                                style="width: 16px; height: 16px;">
                            </i>

                            QR Code
                        </div>

                    </a>

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

                    <!-- BIAR NGIKUT STYLE GLOBAL -->
                    <table id="barangTable"
                        class="table table-hover align-middle mb-0">

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

                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-2">

                                            <!-- Edit -->
                                            <a href="{{ route('barang.edit', $barang->id) }}"
                                                class="btn btn-sm d-flex align-items-center justify-content-center"
                                                style="
                                                    width: 38px;
                                                    height: 38px;
                                                    border-radius: 10px;
                                                    background: rgba(255,255,255,0.05);
                                                    color: var(--color-foreground);
                                                    border: 1px solid rgba(255,255,255,0.08);
                                                ">

                                                <i data-lucide="edit"
                                                    style="width: 18px; height: 18px;">
                                                </i>

                                            </a>

                                            <!-- Delete -->
                                            <button type="button"
                                                class="btn btn-sm d-flex align-items-center justify-content-center"
                                                style="
                                                    width: 38px;
                                                    height: 38px;
                                                    border-radius: 10px;
                                                    background: rgba(255,255,255,0.05);
                                                    color: #ff6b6b;
                                                    border: 1px solid rgba(255,255,255,0.08);
                                                "
                                                onclick="confirmDelete({{ $barang->id }})">

                                                <i data-lucide="trash-2"
                                                    style="width: 18px; height: 18px;">
                                                </i>

                                            </button>

                                            <form id="deleteForm{{ $barang->id }}"
                                                action="{{ route('barang.destroy', $barang->id) }}"
                                                method="POST"
                                                style="display: none;">

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