@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $layout = $isAdmin ? 'layouts.app' : 'layouts.user';
@endphp

@extends($layout)

@section('content')

@if($isAdmin)
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="mb-0 fw-bold">Data Asset</h4>
                    <small class="text-muted">Daftar asset yang tersedia di sistem.</small>
                </div>
                <div class="ms-auto">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah"> <i class="fa fa-plus"></i> Tambah Asset</button>
                </div>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="notification-popup animate-slideDown mb-3">
                        <div class="alert alert-success d-flex align-items-center border-0 shadow-sm" 
                             style="border-radius: 12px; background-color: #F0FDF4;">
                            <div class="d-flex align-items-center justify-content-start flex-grow-1 p-2">
                                <div class="notification-icon me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px; background-color: #DCF9E5;">
                                        <i class="fas fa-check" style="color: #16A34A; font-size: 1.2rem;"></i>
                                    </div>
                                </div>
                                <div class="notification-content">
                                    <h6 class="alert-heading mb-1" style="color: #166534; font-weight: 600;">
                                        Berhasil
                                    </h6>
                                    <p class="mb-0" style="color: #16A34A;">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" style="color: #16A34A; opacity: 0.75;"
                                    data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="notification-popup animate-slideDown mb-3">
                        <div class="alert alert-danger d-flex align-items-center border-0 shadow-sm" 
                             style="border-radius: 12px; background-color: #FEF2F2;">
                            <div class="d-flex align-items-center justify-content-start flex-grow-1 p-2">
                                <div class="notification-icon me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px; background-color: #FEE2E2;">
                                        <i class="fas fa-exclamation-circle" style="color: #DC2626; font-size: 1.2rem;"></i>
                                    </div>
                                </div>
                                <div class="notification-content">
                                    <h6 class="alert-heading mb-1" style="color: #991B1B; font-weight: 600;">
                                        Gagal
                                    </h6>
                                    <p class="mb-0" style="color: #DC2626;">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" style="color: #DC2626; opacity: 0.75;"
                                    data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:48px;">#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th style="width:90px;">Foto</th>
                                <th style="width:150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $a)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration + ($assets->currentPage()-1)*$assets->perPage() }}</td>
                                    <td>{{ $a->kode }}</td>
                                    <td class="fw-semibold">{{ $a->nama }}</td>
                                    <td>{{ optional($a->kategori)->nama_kategori ?? '-' }}</td>
                                    <td>{{ optional($a->lokasi)->nama_lokasi ?? '-' }}</td>
                                    <td>
                                        @php
                                            $fotoUrl = $a->foto ? (request()->getSchemeAndHttpHost() . '/storage/' . $a->foto) : asset('default.png');
                                        @endphp
                                        <div style="width:64px; height:64px; overflow:hidden; border-radius:8px;">
                                            <img src="{{ $fotoUrl }}" alt="foto" style="width:100%; height:100%; object-fit:cover;" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('asset.show', $a->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="fa fa-eye"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-warning btn-edit-asset" title="Edit"
                                                    data-id="{{ $a->id }}"
                                                    data-nama="{{ e($a->nama) }}"
                                                    data-kategori_id="{{ $a->kategori ? $a->kategori->id : '' }}"
                                                    data-lokasi_id="{{ $a->lokasi ? $a->lokasi->id : '' }}"
                                                    data-spesifikasi="{{ e($a->spesifikasi ?? '') }}"
                                                    data-kondisi="{{ $a->kondisi ?? 'Baik' }}"
                                                    data-foto="{{ $a->foto ? (request()->getSchemeAndHttpHost() . '/storage/' . $a->foto) : '' }}"
                                            ><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger btn-delete-asset" data-id="{{ $a->id }}" data-name="{{ e($a->nama) }}" data-foto="{{ $a->foto ? (request()->getSchemeAndHttpHost() . '/storage/' . $a->foto) : '' }}" title="Hapus"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data asset.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $assets->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Tambah (square modal) -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog modal-lg modal-wide modal-fullwidth modal-dialog-centered">
            <div class="modal-content rect-modal">
                <form id="formTambahAsset" action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Asset Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
            <div class="row d-flex align-items-start flex-wrap modal-flex-row">
                <div class="col-md-7 d-flex flex-column">
                                <div class="mb-3">
                                    <label class="form-label">Nama Asset <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Printer Laser HP" value="{{ old('nama') }}" required>
                                    @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select name="kategori_id" class="form-control">
                                            <option value="">- Pilih Kategori -</option>
                                            @foreach($kategoris as $k)
                                                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lokasi</label>
                                        <select name="lokasi_id" class="form-control">
                                            <option value="">- Pilih Lokasi -</option>
                                            @foreach($lokasis as $l)
                                                <option value="{{ $l->id }}" {{ old('lokasi_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_lokasi }}</option>
                                            @endforeach
                                        </select>
                                        @error('lokasi_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <textarea name="spesifikasi" class="form-control" rows="3" placeholder="Contoh: warna putih, ukuran A4...">{{ old('spesifikasi') }}</textarea>
                                    @error('spesifikasi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kondisi</label>
                                    <select name="kondisi" class="form-control">
                                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                    @error('kondisi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-5 d-flex flex-column">
                                <label class="form-label">Preview Foto</label>
                                <div class="border rounded p-2 text-center preview-wrapper" style="min-height:260px; display:flex; align-items:center; justify-content:center;">
                                    <img id="previewFoto" src="" alt="preview" class="preview-img" style="display:none;" />
                                    <span id="previewPlaceholder" class="text-muted">Belum ada foto yang dipilih</span>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Pilih Foto (max 2MB)</label>
                                    <input id="inputFoto" type="file" name="foto" accept="image/*" class="form-control">
                                    <small class="text-muted">JPEG/PNG disarankan.</small>
                                    <div class="text-danger mt-1" id="fotoError" style="display:none;">@error('foto'){{ $message }}@enderror</div>
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <button id="btnClearForm" type="button" class="btn btn-outline-secondary btn-sm">Reset</button>
                                    <button id="btnSubmitForm" type="submit" class="btn btn-primary btn-sm ms-auto">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-center justify-content-start">
                        <small class="text-muted">Semua field dengan tanda * wajib diisi.</small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modern Edit Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modern-modal">
                <form id="formEditAsset" method="POST" enctype="multipart/form-data" action="#">
                    @csrf
                    @method('PUT')
                    <!-- Modern gradient header with icon -->
                    <div class="modal-header bg-gradient-primary text-white border-0">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="fa fa-pencil-alt fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="modal-title mb-0">Edit Asset</h5>
                                <small class="opacity-75">Update informasi asset</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex align-items-start">
                            <!-- Left column: Form fields -->
                            <div class="col-md-7">
                                <div class="modern-form-group mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fa fa-cube text-primary"></i></span>
                                        <div class="form-floating flex-grow-1">
                                            <input type="text" id="edit_nama" name="nama" class="form-control border-start-0" required>
                                            <label>Nama Asset</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-md-6">
                                        <div class="modern-form-group mb-4">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="fa fa-tag text-primary"></i></span>
                                                <div class="form-floating flex-grow-1">
                                                    <select id="edit_kategori" name="kategori_id" class="form-select border-start-0">
                                                        <option value="">- Pilih Kategori -</option>
                                                        @foreach($kategoris as $k)
                                                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>Kategori</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="modern-form-group mb-4">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="fa fa-map-marker-alt text-primary"></i></span>
                                                <div class="form-floating flex-grow-1">
                                                    <select id="edit_lokasi" name="lokasi_id" class="form-select border-start-0">
                                                        <option value="">- Pilih Lokasi -</option>
                                                        @foreach($lokasis as $l)
                                                            <option value="{{ $l->id }}">{{ $l->nama_lokasi }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>Lokasi</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modern-form-group mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fa fa-list-alt text-primary"></i></span>
                                        <div class="form-floating flex-grow-1">
                                            <textarea id="edit_spesifikasi" name="spesifikasi" class="form-control border-start-0" style="min-height:100px"></textarea>
                                            <label>Spesifikasi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modern-form-group">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fa fa-info-circle text-primary"></i></span>
                                        <div class="form-floating flex-grow-1">
                                            <select id="edit_kondisi" name="kondisi" class="form-select border-start-0">
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak Ringan">Rusak Ringan</option>
                                                <option value="Rusak Berat">Rusak Berat</option>
                                            </select>
                                            <label>Kondisi</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Right column: Image preview -->
                            <div class="col-md-5">
                                <div class="modern-preview-wrapper">
                                    <div class="preview-header">
                                        <i class="fa fa-image text-primary me-2"></i>
                                        <span>Foto Asset</span>
                                    </div>
                                    <div class="preview-body">
                                        <div class="preview-container">
                                            <img id="edit_previewFoto" src="" class="img-preview" style="display:none">
                                            <div id="edit_previewPlaceholder" class="preview-placeholder">
                                                <i class="fa fa-cloud-upload-alt"></i>
                                                <span>Belum ada foto yang dipilih</span>
                                            </div>
                                        </div>
                                        <div class="preview-footer">
                                            <div class="upload-wrapper">
                                                <label for="edit_inputFoto" class="btn btn-outline-primary btn-sm w-100">
                                                    <i class="fa fa-upload me-2"></i>Pilih Foto
                                                </label>
                                                <input type="file" id="edit_inputFoto" name="foto" accept="image/*" class="d-none">
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                <i class="fa fa-info-circle me-1"></i>
                                                Format: JPEG/PNG, Maks: 2MB
                                            </small>
                                            <div id="edit_fotoError" class="alert alert-danger mt-2 p-2" style="display:none"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light">
                        <button type="button" id="edit_btnClear" class="btn btn-outline-secondary">
                            <i class="fa fa-undo me-2"></i>Reset
                        </button>
                        <button type="submit" id="edit_btnSubmit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="modalDeleteAsset" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <form id="formDeleteAsset" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-0">
                        <!-- Warning Icon -->
                        <div class="text-center pt-4 pb-3">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 82px; height: 82px; background-color: #FEE2E2;">
                                <i class="fas fa-exclamation-triangle fa-2x" style="color: #DC2626;"></i>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="px-4 pb-3 text-center">
                            <h4 class="text-xl font-semibold text-gray-800 mb-3">Konfirmasi Penghapusan</h4>
                            <div class="asset-preview mb-3 d-flex align-items-center justify-content-center">
                                <img id="deleteAssetThumb" src="" alt="Asset Preview" 
                                     class="rounded-3 shadow-sm" 
                                     style="max-width: 120px; max-height: 120px; object-fit: cover;">
                            </div>
                            <h5 id="deleteAssetName" class="text-lg font-medium text-gray-700 mb-2"></h5>
                            <p class="text-gray-600 mb-0">Apakah Anda yakin ingin menghapus asset ini?</p>
                            <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center" 
                                 style="background-color: #FEF3C7; border: none; border-radius: 12px;">
                                <i class="fas fa-info-circle me-2" style="color: #D97706;"></i>
                                <small class="text-warning" style="color: #92400E;">
                                    Tindakan ini tidak dapat dibatalkan dan semua data terkait akan terhapus.
                                </small>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-4 pb-4 text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-light px-4 py-2" 
                                        data-bs-dismiss="modal"
                                        style="border-radius: 10px; font-weight: 500;">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-danger px-4 py-2" 
                                        style="border-radius: 10px; font-weight: 500; background-color: #DC2626; border: none;">
                                    <i class="fas fa-trash-alt me-2"></i>
                                    Hapus Asset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .modal-content {
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .asset-preview img {
        border: 4px solid #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn-light {
        background-color: #F3F4F6;
        border: 1px solid #E5E7EB;
    }
    
    .btn-light:hover {
        background-color: #E5E7EB;
    }
    
    .btn-danger:hover {
        background-color: #B91C1C !important;
    }
    
    #modalDeleteAsset .modal-dialog {
        max-width: 400px;
    }
    
    .fade-scale {
        transform: scale(0.7);
        opacity: 0;
        transition: all 0.2s linear;
    }
    
    .fade-scale.show {
        transform: scale(1);
        opacity: 1;
    }
    </style>

    @push('scripts')
    <!-- Edit modal handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const editModal = document.getElementById('modalEdit');
            if(!editModal) return;
            
            function resetEditForm(){
                const preview = document.getElementById('edit_previewFoto');
                const placeholder = document.getElementById('edit_previewPlaceholder');
                const input = document.getElementById('edit_inputFoto');
                const error = document.getElementById('edit_fotoError');
                const form = document.getElementById('formEditAsset');
                
                if(preview) { preview.src = ''; preview.style.display = 'none'; }
                if(placeholder) placeholder.style.display = 'flex';
                if(error) { error.style.display = 'none'; error.textContent = ''; }
                if(input) input.value = '';
                if(form) form.reset();
            }
            
            // File input change handler with preview
            document.getElementById('edit_inputFoto')?.addEventListener('change', function(e){
                const file = e.target.files[0];
                const error = document.getElementById('edit_fotoError');
                const preview = document.getElementById('edit_previewFoto');
                const placeholder = document.getElementById('edit_previewPlaceholder');
                
                // Reset first
                if(preview) { preview.src = ''; preview.style.display = 'none'; }
                if(placeholder) placeholder.style.display = 'flex';
                if(error) { error.style.display = 'none'; error.textContent = ''; }
                
                if(!file) return;
                
                // Validate
                if(!file.type?.startsWith('image/')){
                    if(error) {
                        error.textContent = 'File harus berupa gambar (JPEG/PNG)';
                        error.style.display = 'block';
                    }
                    return;
                }
                if(file.size > 2 * 1024 * 1024){
                    if(error) {
                        error.textContent = 'Ukuran file terlalu besar (maksimal 2MB)';
                        error.style.display = 'block';
                    }
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(ev){
                    if(preview) {
                        preview.src = ev.target.result;
                        preview.style.display = 'block';
                    }
                    if(placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            });
            
            // Reset button handler
            document.getElementById('edit_btnClear')?.addEventListener('click', resetEditForm);
            
            // Form submit handler - disable button while saving
            document.getElementById('formEditAsset')?.addEventListener('submit', function(){
                const btn = document.getElementById('edit_btnSubmit');
                if(btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Menyimpan...';
                }
            });
            
            // Open edit modal with data
            document.querySelectorAll('.btn-edit-asset').forEach(function(btn){
                btn.addEventListener('click', function(){
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const kategori = this.getAttribute('data-kategori_id');
                    const lokasi = this.getAttribute('data-lokasi_id');
                    const spesifikasi = this.getAttribute('data-spesifikasi');
                    const kondisi = this.getAttribute('data-kondisi');
                    const foto = this.getAttribute('data-foto');
                    
                    // Set form action
                    const form = document.getElementById('formEditAsset');
                    if(form) form.action = `${window.location.origin}/asset/${id}`;
                    
                    // Populate fields
                    if(document.getElementById('edit_nama')) document.getElementById('edit_nama').value = nama || '';
                    if(document.getElementById('edit_kategori')) document.getElementById('edit_kategori').value = kategori || '';
                    if(document.getElementById('edit_lokasi')) document.getElementById('edit_lokasi').value = lokasi || '';
                    if(document.getElementById('edit_spesifikasi')) document.getElementById('edit_spesifikasi').value = spesifikasi || '';
                    if(document.getElementById('edit_kondisi')) document.getElementById('edit_kondisi').value = kondisi || 'Baik';
                    
                    // Show existing photo
                    const preview = document.getElementById('edit_previewFoto');
                    const placeholder = document.getElementById('edit_previewPlaceholder');
                    if(foto && preview) {
                        preview.src = foto;
                        preview.style.display = 'block';
                        if(placeholder) placeholder.style.display = 'none';
                    } else {
                        if(preview) preview.style.display = 'none';
                        if(placeholder) placeholder.style.display = 'flex';
                    }
                    
                    // Show modal
                    const modal = new bootstrap.Modal(editModal);
                    modal.show();
                });
            });
            
            // Reset form when modal is hidden
            editModal.addEventListener('hidden.bs.modal', resetEditForm);
            
            // Handle validation errors - reopen modal
            @if($errors->any())
                try {
                    const modal = new bootstrap.Modal(editModal);
                    modal.show();
                } catch(e) { console.warn('Failed to auto-open edit modal', e); }
            @endif
        });
    </script>
    
    <!-- Add modal handling -->
    <script>
        (function(){
            const $ = id => document.getElementById(id);
            const inputFoto = $('inputFoto');
            const previewFoto = $('previewFoto');
            const previewPlaceholder = $('previewPlaceholder');
            const fotoError = $('fotoError');
            const form = $('formTambahAsset');
            const btnClear = $('btnClearForm');
            const btnSubmit = $('btnSubmitForm');
            const modalEl = document.getElementById('modalTambah');

            function resetPreview() {
                try{
                    if(previewFoto){ previewFoto.src = ''; previewFoto.style.display = 'none'; }
                    if(previewPlaceholder) previewPlaceholder.style.display = 'block';
                    if(fotoError) fotoError.style.display = 'none';
                    if(inputFoto) inputFoto.value = '';
                }catch(e){ console.warn('resetPreview error', e); }
            }

            if(inputFoto){
                inputFoto.addEventListener('change', function(e){
                    try{
                        if(fotoError) fotoError.style.display = 'none';
                        const file = e.target.files[0];
                        if(!file) { resetPreview(); return; }
                        if(!file.type || !file.type.startsWith('image/')){
                            if(fotoError){ fotoError.textContent = 'File bukan gambar.'; fotoError.style.display = 'block'; }
                            resetPreview();
                            return;
                        }
                        if(file.size > 2 * 1024 * 1024){
                            if(fotoError){ fotoError.textContent = 'Ukuran file terlalu besar (maks 2MB).'; fotoError.style.display = 'block'; }
                            resetPreview();
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(ev){
                            if(previewFoto){ previewFoto.src = ev.target.result; previewFoto.style.display = 'block'; }
                            if(previewPlaceholder) previewPlaceholder.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    }catch(err){ console.error('file change handler error', err); }
                });
            }

            if(btnClear && form){ btnClear.addEventListener('click', function(){ resetPreview(); try{ form.reset(); }catch(e){} }); }
            if(form){ form.addEventListener('submit', function(){ try{ if(btnSubmit){ btnSubmit.disabled = true; btnSubmit.textContent = 'Menyimpan...'; } }catch(e){} }); }
            if(modalEl){ try{ modalEl.addEventListener('hidden.bs.modal', function(){ resetPreview(); try{ if(form) form.reset(); }catch(e){} try{ if(btnSubmit){ btnSubmit.disabled = false; btnSubmit.textContent = 'Simpan'; } }catch(e){} }); modalEl.addEventListener('show.bs.modal', function(){ if(fotoError) fotoError.style.display = 'none'; }); }catch(e){ console.warn('modal event binding failed', e); } }
        })();
    </script>
    <script>
        // Instead of moving the modal DOM into the card, create a "card-scoped" overlay
        // that visually confines the backdrop and centers the modal over the card area.
        document.addEventListener('DOMContentLoaded', function(){
            var modal = document.getElementById('modalTambah');
            if(!modal) return;
            var card = document.querySelector('.card');
            var bsModal = bootstrap.Modal.getOrCreateInstance(modal);

            // overlay element (created on demand)
            var scopedOverlay = null;
            var positioned = false;

            function createOverlay() {
                if(!card) return null;
                var ov = document.createElement('div');
                ov.className = 'card-scoped-overlay';
                // ensure overlay covers the card area
                ov.style.position = 'absolute';
                ov.style.inset = '0';
                ov.style.background = 'rgba(0,0,0,0.35)';
                ov.style.zIndex = 1050; // just under Bootstrap modal backdrop (which is 1055+); modal will be placed above
                ov.style.borderRadius = getComputedStyle(card).borderRadius || '8px';
                card.appendChild(ov);
                return ov;
            }

            function positionModalOverCard() {
                if(!card || !modal) return;
                var cardRect = card.getBoundingClientRect();
                // compute center point
                var winLeft = window.scrollX || window.pageXOffset;
                var winTop = window.scrollY || window.pageYOffset;
                var centerX = cardRect.left + cardRect.width / 2 + winLeft;
                var centerY = cardRect.top + cardRect.height / 2 + winTop;

                // position modal dialog (the .modal-dialog element) fixed so it's visually over the card
                var dialog = modal.querySelector('.modal-dialog');
                if(!dialog) return;
                // ensure dialog is above overlay
                dialog.style.zIndex = 1060;
                dialog.style.position = 'fixed';
                dialog.style.left = '50%';
                dialog.style.top = '50%';
                dialog.style.transform = 'translate(-50%, -50%)';
                // limit width so it doesn't overflow the card too much
                var maxW = Math.min(cardRect.width - 40, 960); // 20px padding each side
                dialog.style.maxWidth = Math.max(320, maxW) + 'px';
                dialog.style.width = 'auto';
                positioned = true;
            }

            function resetModalPosition() {
                if(!modal) return;
                var dialog = modal.querySelector('.modal-dialog');
                if(!dialog) return;
                dialog.style.position = '';
                dialog.style.left = '';
                dialog.style.top = '';
                dialog.style.transform = '';
                dialog.style.zIndex = '';
                dialog.style.maxWidth = '';
                dialog.style.width = '';
                positioned = false;
            }

            modal.addEventListener('show.bs.modal', function(){
                // create overlay inside card
                if(card) {
                    scopedOverlay = createOverlay();
                }
                // slightly delay to let Bootstrap insert backdrops if any, then position
                setTimeout(function(){ positionModalOverCard(); }, 10);

                // handle viewport changes while modal is open
                window.addEventListener('resize', positionModalOverCard);
                window.addEventListener('scroll', positionModalOverCard, true);
            });

            modal.addEventListener('hidden.bs.modal', function(){
                // remove overlay and reset dialog styles
                if(scopedOverlay && scopedOverlay.parentElement) scopedOverlay.parentElement.removeChild(scopedOverlay);
                scopedOverlay = null;
                resetModalPosition();
                window.removeEventListener('resize', positionModalOverCard);
                window.removeEventListener('scroll', positionModalOverCard, true);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const modalEl = document.getElementById('modalDeleteAsset');
            if(!modalEl) return;
            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const form = document.getElementById('formDeleteAsset');
            const nameEl = document.getElementById('deleteAssetName');
            const thumb = document.getElementById('deleteAssetThumb');

            document.querySelectorAll('.btn-delete-asset').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name') || '';
                    const foto = this.getAttribute('data-foto') || '';
                    if(form) form.action = (window.location.origin || '') + '/asset/' + id;
                    if(nameEl) nameEl.textContent = name;
                    if(thumb) { if(foto) { thumb.src = foto; thumb.style.display = 'block'; } else { thumb.src = ''; thumb.style.display = 'none'; } }
                    bsModal.show();
                });
            });
        });
    </script>
    @endpush

    @if ($errors->any())
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                try{
                    var modal = new bootstrap.Modal(document.getElementById('modalTambah'));
                    modal.show();
                }catch(e){ console.warn('Failed to auto-open modalTambah', e); }
            });
        </script>
        @endpush
    @endif

    <style>
        /* Notification Animations and Styles */
        .notification-popup {
            animation: slideDown 0.5s ease-out forwards, fadeIn 0.5s ease-out forwards;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .notification-popup .alert {
            transition: all 0.3s ease;
        }
        
        .notification-popup .alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
        }
        
        .notification-icon {
            transition: all 0.3s ease;
        }
        
        .notification-popup:hover .notification-icon {
            transform: scale(1.1);
        }
        
        .btn-close {
            transition: all 0.2s ease;
        }
        
        .btn-close:hover {
            transform: rotate(90deg);
        }

        /* Small, scoped styles to modernize asset cards and thumbnails */
        .asset-card { transition: transform .18s ease, box-shadow .18s ease; border-radius: 12px; }
        .asset-card:hover { transform: translateY(-6px); box-shadow: 0 10px 30px rgba(24,39,75,0.08); }
        .asset-card .thumb { width:72px; height:72px; background-size:cover; background-position:center; border-radius:10px; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02); }
        /* adjust modal preview look */
        #previewFoto { border-radius:8px; max-height:220px; }
        #previewPlaceholder { color: #6b7280; }
        /* rectangular modal styles (compact, prefers width growth rather than vertical growth) */
        .rect-modal { overflow: hidden; border-radius: 10px; }
        .rect-modal .modal-body { padding: 0.85rem; }
        
        /* Modern Edit Modal Styles */
        .modern-modal {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
        }
        .header-icon {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modern-form-group .input-group {
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            border-radius: 12px;
            overflow: hidden;
        }
        .modern-form-group .form-control,
        .modern-form-group .form-select {
            border-color: #e5e7eb;
            padding-left: 0.5rem;
        }
        .modern-form-group .input-group-text {
            width: 46px;
            justify-content: center;
        }
        .form-floating > label {
            padding-left: 0.5rem;
        }
        .modern-preview-wrapper {
            background: #f9fafb;
            border-radius: 16px;
            overflow: hidden;
        }
        .preview-header {
            padding: 1rem;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 500;
        }
        .preview-body {
            padding: 1rem;
        }
        .preview-container {
            aspect-ratio: 4/3;
            background: #fff;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .preview-placeholder {
            text-align: center;
            color: #9ca3af;
        }
        .preview-placeholder i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .img-preview {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        /* Ensure modern modal form controls look consistent */
        .modern-form-group .form-control:focus,
        .modern-form-group .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
        }
        .modern-modal .btn-primary {
            background: #4f46e5;
            border-color: #4f46e5;
        }
        .modern-modal .btn-outline-secondary {
            color: #6b7280;
            border-color: #e5e7eb;
        }
        .modern-modal .btn-outline-secondary:hover {
            background: #f9fafb;
            color: #374151;
        }
        /* moderate width modal (not too big) */
        .modal-dialog.modal-lg .rect-modal { max-width: 760px; width: 100%; }
        /* prefer increasing width (left-to-right). limit vertical growth with max-height on the modal body */
        @media (min-width: 768px) {
            .modal-dialog.modal-lg .rect-modal { width: min(80vw, 760px); }
            .modal-dialog.modal-lg .rect-modal .modal-content { height: auto; }
            .modal-dialog.modal-lg .rect-modal .modal-body { max-height: 65vh; overflow: auto; }
        }
        @media (max-width: 767px) {
            /* on small screens fallback to normal modal with internal scrolling */
            .modal-dialog.modal-lg .rect-modal { width: 95vw; }
            .modal-dialog.modal-lg .rect-modal .modal-body { max-height: 70vh; overflow:auto; }
        }
        /* fullwidth modal helper: allows modal to use more horizontal space up to a cap */
        .modal-fullwidth { width: 95vw; max-width: 1000px; }
        /* center modal vertically and horizontally by fixing the dialog when shown */
        .modal.show .modal-dialog.modal-fullwidth {
            position: fixed;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
        }

        /* Stronger centering fallback: use flex centering on the modal element itself */
        .modal.show {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem !important;
        }
        /* Ensure the modal dialog does not stay absolute/shifted when using flex centering */
        .modal.show .modal-dialog.modal-fullwidth {
            position: relative !important;
            top: auto !important;
            left: auto !important;
            transform: none !important;
            margin: 0 auto !important;
        }
        .modal-flex-row { gap: 1rem; }
        .modal-flex-row > [class*='col-'] { display: flex; }
        /* make left and right columns stretch horizontally */
        .modal-flex-row .col-md-7 { flex: 1 1 60%; }
        .modal-flex-row .col-md-5 { flex: 0 0 35%; }
        @media (max-width: 991px) {
            .modal-fullwidth { width: 95vw; max-width: 760px; }
            .modal-flex-row .col-md-7, .modal-flex-row .col-md-5 { flex: 0 0 100%; }
        }
        /* make card a positioning context so modal can be placed inside it */
        .card { position: relative; }
        /* preview wrapper and image fit */
    .preview-wrapper { position: relative; width:100%; height:100%; min-height:180px; display:flex; align-items:center; justify-content:center; }
    .preview-img { width:100%; height:100%; border-radius:8px; object-fit:cover; display:block; }
        /* responsive tweaks */
        @media (max-width: 576px){
            .asset-card .thumb { width:64px; height:64px; }
        }
    </style>

@else
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="relative px-6 py-8 sm:px-8">
                <div class="absolute right-0 top-0 w-1/3 h-full opacity-5">
                    <svg class="w-full h-full text-blue-600" fill="currentColor" viewBox="0 0 100 100">
                        <defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1"/></pattern></defs>
                        <rect width="100" height="100" fill="url(#grid)"/>
                    </svg>
                </div>
                <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h1 class="text-2xl font-bold text-gray-900">Data Asset</h1>
                        <p class="text-base text-gray-600">Daftar asset yang tersedia di sistem</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard.user') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 shadow-sm">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="animate-slideDown">
                <div class="rounded-xl bg-green-50 p-4 text-sm text-green-600 flex items-start border border-green-100">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($assets as $a)
                        @php
                            $fotoUrl = $a->foto ? (request()->getSchemeAndHttpHost() . '/storage/' . $a->foto) : asset('default.png');
                            $kondisiClass = match(strtolower($a->kondisi ?? 'baik')) {
                                'baik' => 'bg-green-100 text-green-800',
                                'rusak ringan' => 'bg-yellow-100 text-yellow-800',
                                'rusak berat' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <div class="group">
                            <article class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                                <div class="relative aspect-[4/3] mb-4 rounded-xl overflow-hidden bg-gray-100">
                                    <img src="{{ $fotoUrl }}" alt="{{ $a->nama }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-semibold text-gray-900 text-lg leading-tight">{{ $a->nama }}</h3>
                                        <span class="flex-shrink-0 px-2.5 py-1 text-xs rounded-full {{ $kondisiClass }}">{{ $a->kondisi ?? 'Baik' }}</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        @if($a->kategori)
                                            <div class="flex items-center text-sm text-gray-600">{{ optional($a->kategori)->nama_kategori }}</div>
                                        @endif
                                        @if($a->lokasi)
                                            <div class="flex items-center text-sm text-gray-600">{{ optional($a->lokasi)->nama_lokasi }}</div>
                                        @endif
                                    </div>
                                    <div class="pt-3">
                                        <a href="{{ route('asset.show', $a->id) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-200 font-medium text-sm">Lihat Detail</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada data asset</h3>
                                <p class="text-gray-500">Asset akan muncul di sini setelah admin menambahkannya</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
