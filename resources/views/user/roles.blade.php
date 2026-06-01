<x-app-layout title="Role & Akses Menu - Alur Confused" icon='<i data-lucide="shield" class="me-3"></i> Role & Akses Menu'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-2">
                <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
            </div>
            <div class="col-md-10">
                <h4 class="mb-0" style="color: var(--color-foreground);">Role & Akses Menu</h4>
                <p class="text-muted mb-0">Daftar role dan akses menu sistem</p>
            </div>
        </div>

        <!-- Roles and Their Menu Access -->
        <div class="row">
            @foreach($roles as $role)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
                        <div class="card-header border-0" style="background: var(--color-foreground); color: white; border-radius: 12px 12px 0 0;">
                            <h6 class="mb-0">
                                <p class="d-flex align-items-center mb-0">
                                    <i data-lucide="shield" style="margin-right: 8px; width: 20px; height: 20px;"></i> 
                                    {{ $role->name }}
                                </p>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: var(--color-foreground);">Akses Menu:</label>
                                <div>
                                    @if($role->name == 'Owner')
                                        <span class="badge bg-success me-1 mb-1">Dashboard</span>
                                        <span class="badge bg-success me-1 mb-1">Barang</span>
                                        <span class="badge bg-success me-1 mb-1">Persediaan</span>
                                        <span class="badge bg-success me-1 mb-1">Penyesuaian Persediaan</span>
                                        <span class="badge bg-success me-1 mb-1">Barang Masuk</span>
                                        <span class="badge bg-success me-1 mb-1">Pesanan</span>
                                        <span class="badge bg-success me-1 mb-1">Laporan</span>
                                        <span class="badge bg-success me-1 mb-1">User</span>
                                    @elseif($role->name == 'Persediaan')
                                        <span class="badge bg-info me-1 mb-1">Dashboard</span>
                                        <span class="badge bg-info me-1 mb-1">Barang</span>
                                        <span class="badge bg-info me-1 mb-1">Persediaan</span>
                                        <span class="badge bg-info me-1 mb-1">Penyesuaian Persediaan</span>
                                        <span class="badge bg-info me-1 mb-1">Barang Masuk</span>
                                    @elseif($role->name == 'Produksi')
                                        <span class="badge bg-warning me-1 mb-1">Dashboard</span>
                                        <span class="badge bg-warning me-1 mb-1">Persediaan</span>
                                        <span class="badge bg-warning me-1 mb-1">Pesanan</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: var(--color-foreground);">User dengan Role ini:</label>
                                <div>
                                    @php
                                        $usersWithRole = \App\Models\User::role($role->name)->get();
                                    @endphp
                                    @foreach($usersWithRole as $user)
                                        <span class="badge bg-primary me-1 mb-1">{{ $user->name }}</span>
                                    @endforeach
                                    @if($usersWithRole->isEmpty())
                                        <span class="badge bg-secondary">Tidak ada user</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Role Descriptions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
                    <div class="card-header border-0" style="background: var(--color-foreground); color: white; border-radius: 12px 12px 0 0;">
                        <h6 class="mb-0">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="info" style="margin-right: 8px; width: 20px; height: 20px;"></i> Deskripsi Role
                            </p>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-success">Owner</h6>
                                <p class="text-muted small mb-3">Memiliki akses penuh ke seluruh sistem termasuk semua menu dan fitur. Dapat mengelola user dan melihat semua laporan.</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-info">Persediaan</h6>
                                <p class="text-muted small mb-3">Fokus pada pengelolaan stok dan inventory. Dapat akses menu Barang, Persediaan, Penyesuaian Persediaan, dan Barang Masuk.</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-warning">Produksi</h6>
                                <p class="text-muted small mb-3">Fokus pada proses produksi dan pesanan. Dapat akses menu Persediaan dan Pesanan untuk koordinasi produksi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
