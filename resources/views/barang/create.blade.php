<x-app-layout title="Tambah Barang - Alur Confused" icon='<i data-lucide="plus" class="me-3"></i> Tambah Barang'>
    <div class="container-fluid">

        <!-- Form -->
        <div class="card border-0 shadow-sm"
             style="background: var(--color-background); border-radius: 16px;">

            <div class="card-body" style="padding: 2rem;">

                <form id="barangForm" action="{{ route('barang.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">

                        <!-- Kode Barang -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control minimal-input @error('kode_barang') is-invalid @enderror"
                                    id="kode_barang"
                                    name="kode_barang"
                                    value="{{ old('kode_barang') }}"
                                    placeholder="Kode Barang"
                                    required
                                >

                                <label for="kode_barang">
                                    Kode Barang Otomatis*
                                </label>

                                @error('kode_barang')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        

                        <!-- Nama Barang -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control minimal-input @error('nama_barang') is-invalid @enderror"
                                    id="nama_barang"
                                    name="nama_barang"
                                    value="{{ old('nama_barang') }}"
                                    placeholder="Nama Barang"
                                    required
                                >

                                <label for="nama_barang">
                                    Nama Barang *
                                </label>

                                @error('nama_barang')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Harga Jual -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number"
                                        class="form-control minimal-input @error('harga') is-invalid @enderror"
                                        id="harga"
                                        name="harga"
                                        value="{{ old('harga') }}"
                                        placeholder="Harga Barang"
                                        required>

                                    <label for="harga">Harga Jual *</label>

                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Harga Beli (HPP) -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number"
                                        class="form-control minimal-input @error('harga_beli') is-invalid @enderror"
                                        id="harga_beli"
                                        name="harga_beli"
                                        value="{{ old('harga_beli') }}"
                                        placeholder="Harga Beli (HPP)"
                                        required>

                                    <label for="harga_beli">Harga Beli (HPP) *</label>

                                    @error('harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6">
                                <div class="minimal-select-container">
                                    <label for="kategori" class="minimal-label">Kategori *</label>

                                    <select class="form-select minimal-select @error('kategori') is-invalid @enderror"
                                        id="kategori"
                                        name="kategori"
                                        required>

                                        <option value="">Pilih Kategori</option>
                                        <option value="Jaket" {{ old('kategori') == 'Jaket' ? 'selected' : '' }}>Jaket</option>
                                        <option value="Rompi" {{ old('kategori') == 'Rompi' ? 'selected' : '' }}>Rompi</option>
                                        <option value="Kerah" {{ old('kategori') == 'Kerah' ? 'selected' : '' }}>Kerah</option>
                                        <option value="Baju" {{ old('kategori') == 'Baju' ? 'selected' : '' }}>Baju</option>
                                        <option value="Celana_pendek" {{ old('kategori') == 'Celana_pendek' ? 'selected' : '' }}>Celana Pendek</option>
                                        <option value="Celana_panjang" {{ old('kategori') == 'Celana_panjang' ? 'selected' : '' }}>Celana Panjang</option>

                                    </select>

                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Satuan -->
                            <div class="col-md-6">
                                <div class="minimal-select-container">
                                    <label for="satuan" class="minimal-label">Satuan *</label>

                                    <select class="form-select minimal-select @error('satuan') is-invalid @enderror"
                                        id="satuan"
                                        name="satuan"
                                        required>

                                        <option value="">Pilih Satuan</option>
                                        <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>PCS</option>
                                        <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>KG</option>
                                        <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram</option>

                                    </select>

                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Submit Buttons -->
<div class="d-flex justify-content-end gap-3 mt-5">
    <a href="{{ route('barang.index') }}" class="btn btn-light minimal-btn-secondary">
        <p class="d-flex align-items-center mb-0">
            <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
        </p>
    </a>
    <button 
        type="submit"
        form="barangForm"
        class="btn btn-primary minimal-btn-primary">
            <p class="d-flex align-items-center mb-0">
                <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Supplier
            </p>
    </button>
</div>
</x-app-layout>