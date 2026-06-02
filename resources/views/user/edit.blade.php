<x-app-layout title="Edit User - Alur Confused" icon='<i data-lucide="user-edit" class="me-3"></i> Edit User'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Edit User</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Edit data user: {{ $user->name }}</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
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
                                       value="{{ old('username', $user->username) }}"
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
                                       value="{{ old('email', $user->email) }}"
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
                                        @if($roleItem->name == 'Owner' && $ownerExists && $user->roles->first()?->name != 'Owner')
                                            <!-- Skip Owner role if already exists and current user is not owner -->
                                        @else
                                            <option value="{{ $roleItem->name }}" 
                                                    {{ (old('role') ?: $user->roles->first()?->name) == $roleItem->name ? 'selected' : '' }}>
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
                                       placeholder="Password Baru">
                                <label for="password">Password Baru (opsional)</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted mt-2">Kosongkan jika tidak ingin mengubah password</small>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" 
                                       class="form-control minimal-input" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Konfirmasi Password Baru">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
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
                                <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Update User
                            </p>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for role selection
        $('#role').select2({
            placeholder: 'Pilih Role',
            allowClear: false,
            width: '100%'
        });

        // Auto-hide alert after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
@endpush
