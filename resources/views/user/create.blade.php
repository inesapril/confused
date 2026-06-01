<x-app-layout title="Tambah User - Alur Confused" icon='<i data-lucide="user-plus" class="me-3"></i> Tambah User'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Tambah User</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Tambahkan pengguna baru ke sistem</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Nama Lengkap"
                                       required>
                                <label for="name">Nama Lengkap *</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username') }}"
                                       placeholder="Username"
                                       required>
                                <label for="username">Username *</label>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" 
                                       class="form-control minimal-input @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Email"
                                       required>
                                <label for="email">Email *</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <div class="minimal-select-container">
                                <label for="role" class="minimal-label">Role *</label>
                                <select class="form-select minimal-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $roleItem)
                                        @if($roleItem->name == 'Owner' && $ownerExists)
                                            <!-- Skip Owner role if already exists -->
                                        @else
                                            <option value="{{ $roleItem->name }}" {{ old('role') == $roleItem->name ? 'selected' : '' }}>
                                                {{ $roleItem->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" 
                                       class="form-control minimal-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Password"
                                       required>
                                <label for="password">Password *</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" 
                                       class="form-control minimal-input" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Konfirmasi Password"
                                       required>
                                <label for="password_confirmation">Konfirmasi Password *</label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('user.index') }}" class="btn btn-light minimal-btn-secondary">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                            </p>
                        </a>
                        <button type="submit" class="btn btn-primary minimal-btn-primary">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan User
                            </p>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#role').select2({
                placeholder: 'Pilih Role',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        });
    </script>

    <style>
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

        .minimal-select-container {
            position: relative;
        }

        .minimal-label {
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.5rem;
            display: block;
        }

        .minimal-select {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 14px;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .minimal-select:focus {
            border-color: #4AC8EA;
            box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
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

        /* Select2 Minimal Styling */
        .select2-container--default .select2-selection--single {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            height: 56px;
            padding: 1rem;
            background: #ffffff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
            color: #495057;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #4AC8EA;
            box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
        }

        .select2-dropdown {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #4AC8EA;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
    </style>
</x-app-layout>
