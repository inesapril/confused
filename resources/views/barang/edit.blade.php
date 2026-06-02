<x-app-layout title="Edit Barang - Alur Confused" icon='<i data-lucide="plus" class="me-3"></i> Edit Barang'>
    <div class="container-fluid">

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">
                    Edit Barang Baru
                </h4>

                <p class="text-muted mb-0" style="font-size: 14px;">
                    Edit data barang ke dalam inventory
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="card border-0 shadow-sm"
             style="background: var(--color-background); border-radius: 16px;">

            <div class="card-body" style="padding: 2rem;">

                <form id="barangForm" action="{{ route('barang.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT')

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
                                    value="{{ old('kode_barang', $barang->kode_barang) }}"
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
                                    value="{{ old('nama_barang', $barang->nama_barang) }}"
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
                                <input
                                    type="number"
                                    class="form-control minimal-input @error('harga') is-invalid @enderror"
                                    id="harga"
                                    name="harga"
                                    value="{{ old('harga', $barang->harga) }}"
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
                                <input
                                    type="number"
                                    class="form-control minimal-input @error('harga_beli') is-invalid @enderror"
                                    id="harga_beli"
                                    name="harga_beli"
                                    value="{{ old('harga_beli', $barang->harga_beli ?? '') }}"
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

                                <select
                                    class="form-select minimal-select @error('kategori') is-invalid @enderror"
                                    id="kategori"
                                    name="kategori"
                                    required>

                                    <option value="">Pilih Kategori</option>

                                    <option value="Baju" {{ old('kategori', $barang->kategori) == 'Baju' ? 'selected' : '' }}>Baju</option>
                                    <option value="Celana" {{ old('kategori', $barang->kategori) == 'Celana' ? 'selected' : '' }}>Celana</option>
                                    <option value="Jaket" {{ old('kategori', $barang->kategori) == 'Jaket' ? 'selected' : '' }}>Jaket</option>
                                    <option value="Rompi" {{ old('kategori', $barang->kategori) == 'Rompi' ? 'selected' : '' }}>Rompi</option>
                                    <option value="Kerah" {{ old('kategori', $barang->kategori) == 'Kerah' ? 'selected' : '' }}>Kerah</option>

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

                                <select
                                    class="form-select minimal-select @error('satuan') is-invalid @enderror"
                                    id="satuan"
                                    name="satuan"
                                    required>

                                    <option value="">Pilih Satuan</option>

                                    <option value="pcs" {{ old('satuan', $barang->satuan) == 'pcs' ? 'selected' : '' }}>PCS</option>
                                    <option value="kg" {{ old('satuan', $barang->satuan) == 'kg' ? 'selected' : '' }}>KG</option>
                                    <option value="gram" {{ old('satuan', $barang->satuan) == 'gram' ? 'selected' : '' }}>Gram</option>

                                </select>

                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('barang.index') }}"
            class="btn btn-light px-4">

                <i data-lucide="x" class="me-2"></i>
                Batal
            </a>

            <button
                type="submit"
                form="barangForm"
                class="btn btn-primary px-4"
                style="background:#4AC8EA; border:none;">

                <i data-lucide="save" class="me-2"></i>
                Simpan Barang
            </button>

        </div>
    </div>

</x-app-layout>