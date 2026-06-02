<x-app-layout title="Manajemen User - Alur Confused" icon='<i data-lucide="users" class="me-3"></i> Manajemen User'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Manajemen User</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Kelola pengguna sistem dan role mereka</p>
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

        <!-- Search and Add Button Row -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('user.create') }}" class="btn btn-primary" style="background: linear-gradient(90deg, #4AC8EA 0%, #4AC8EA 100%); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah User
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
                    <table id="usersTable" class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: var(--color-foreground);">ID</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Nama</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Username</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Email</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Role</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td style="font-weight: 500;">{{ $user->id }}</td>
                                    <td style="font-weight: 500;">{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->roles->first())
                                            @php
                                                $role = $user->roles->first();
                                                $badgeClass = match($role->name) {
                                                    'Owner' => 'bg-success',
                                                    'Persediaan' => 'bg-info',
                                                    'Produksi' => 'bg-warning',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $role->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">No Role</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="edit" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->id }})">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="trash-2" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </button>
                                            <form id="deleteForm{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;">
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
document.addEventListener('DOMContentLoaded', function() {
    // Wait for all scripts to load
    setTimeout(function() {
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            try {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#usersTable')) {
                    $('#usersTable').DataTable().destroy();
                }
                
                var table = $('#UsersTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
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

function confirmDelete(userId) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus user ini?',
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
            document.getElementById('deleteForm' + userId).submit();
        }
    });
}
</script>

@include('components.table-styles')
</x-app-layout>
