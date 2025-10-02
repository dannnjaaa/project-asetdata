@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-box-open text-primary me-2" style="font-size: 1.5rem;"></i>
                <h5 class="mb-0">Data Barang</h5>
            </div>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createBarangModal">
                <i class="fa fa-plus"></i>Tambah Barang
            </button>
            @endif
        </div>
        <div class="card-body">
            @include('components.notification-modal')
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>INFORMASI BARANG</th>
                            <th>KATEGORI</th>
                            <th>SPESIFIKASI</th>
                            <th style="width: 120px;">STATUS</th>
                            <th class="text-center" style="width: 100px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang as $i => $b)
                        <tr>
                            <td class="text-center">{{ $i+1 }}</td>
                            <td>
                                <div class="item-card">
                                    <div class="item-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="item-info">
                                        <div class="item-title">{{ $b->nama_barang }}</div>
                                        <div class="item-subtitle">ID: #{{ $b->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge bg-light text-dark">
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    {{ $b->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="display: block; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $b->spesifikasi }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $kondisiClass = [
                                        'Baik' => 'bg-success text-white',
                                        'Rusak Ringan' => 'bg-warning text-dark',
                                        'Rusak Berat' => 'bg-danger text-white'
                                    ][$b->kondisi] ?? 'bg-secondary text-white';
                                @endphp
                                <span class="status-badge {{ $kondisiClass }}" style="max-width: 120px; display: inline-block; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; font-size: 0.85rem; vertical-align: middle;" title="{{ $b->kondisi }}">
                                    {{ $b->kondisi }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-info btn-sm" onclick="showBarang({{ json_encode($b) }})" title="Lihat Detail">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @if(auth()->check() && auth()->user()->role === 'admin')
                                    <button type="button" class="btn btn-warning btn-sm" onclick="editBarang({{ json_encode($b) }})" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteBarang('{{ $b->id }}', '{{ $b->nama_barang }}')" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-box-open text-muted"></i>
                                    <h6 class="mb-1">Belum Ada Data Barang</h6>
                                    <p class="text-muted small mb-0">Silakan tambahkan barang baru untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- All Modals Section -->
<!-- Show Modal -->
<div class="modal modal-blur fade" id="showBarangModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-box-open text-primary"></i>
                    Detail Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary p-3 text-white">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1" id="detailNamaBarang"></h6>
                                <small class="text-muted" id="detailId"></small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Kategori</label>
                            <div class="detail-value">
                                <i class="fas fa-tag text-primary me-1"></i>
                                <span id="detailKategori"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Kondisi</label>
                            <div class="detail-value">
                                <span id="detailKondisiBadge" class="status-badge"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Spesifikasi</label>
                            <div class="detail-value">
                                <i class="fas fa-clipboard-list text-secondary me-1"></i>
                                <span id="detailSpesifikasi" class="text-pre-wrap"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .detail-item {
                        background: #fff;
                        border: 1px solid rgba(0,0,0,.125);
                        border-radius: .25rem;
                        padding: 1rem;
                    }
                    .detail-value {
                        font-weight: 500;
                        margin-top: .25rem;
                    }
                    .text-pre-wrap {
                        white-space: pre-wrap;
                    }
                </style>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal modal-blur fade" id="editBarangModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-edit text-warning"></i>
                    Edit Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBarangForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <div class="alert alert-info d-flex align-items-center mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Lengkapi informasi barang dengan benar</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_nama_barang" class="form-label">
                                    <i class="fas fa-box text-primary me-1"></i>
                                    Nama Barang
                                </label>
                                <input type="text" class="form-control" id="edit_nama_barang" name="nama_barang" required>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_kategori_id" class="form-label">
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    Kategori
                                </label>
                                <select class="form-select" id="edit_kategori_id" name="kategori_id" required>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_spesifikasi" class="form-label">
                                    <i class="fas fa-clipboard-list text-primary me-1"></i>
                                    Spesifikasi
                                </label>
                                <textarea class="form-control" id="edit_spesifikasi" name="spesifikasi" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_kondisi" class="form-label">
                                    <i class="fas fa-info-circle text-primary me-1"></i>
                                    Kondisi
                                </label>
                                <select class="form-select" id="edit_kondisi" name="kondisi" required>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal modal-blur fade" id="deleteBarangModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-trash text-danger"></i>
                    Hapus Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card bg-danger text-white mb-3">
                    <div class="card-body d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <h6 class="mb-0">Konfirmasi Penghapusan</h6>
                            <small>Tindakan ini tidak dapat dibatalkan</small>
                        </div>
                    </div>
                </div>
                
                <p class="mb-0">Apakah Anda yakin ingin menghapus barang <strong id="deleteBarangName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <form id="deleteBarangForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal modal-blur fade" id="createBarangModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle text-primary"></i>
                    Tambah Barang Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <div class="alert alert-info d-flex align-items-center mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Silakan isi semua informasi barang yang diperlukan</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama_barang" class="form-label">
                                    <i class="fas fa-box text-primary me-1"></i>
                                    Nama Barang
                                </label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required 
                                       placeholder="Masukkan nama barang">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="kategori_id" class="form-label">
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    Kategori
                                </label>
                                <select class="form-select" id="kategori_id" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="spesifikasi" class="form-label">
                                    <i class="fas fa-clipboard-list text-primary me-1"></i>
                                    Spesifikasi
                                </label>
                                <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="3" required
                                          placeholder="Masukkan spesifikasi barang"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="kondisi" class="form-label">
                                    <i class="fas fa-info-circle text-primary me-1"></i>
                                    Kondisi
                                </label>
                                <select class="form-select" id="kondisi" name="kondisi" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Modals
    const showModal = new bootstrap.Modal(document.getElementById('showBarangModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    const editModal = new bootstrap.Modal(document.getElementById('editBarangModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteBarangModal'), {
        backdrop: 'static',
        keyboard: false
    });

    // Show Barang Details
    window.showBarang = function(data) {
        // Set modal content
        document.getElementById('detailNamaBarang').textContent = data.nama_barang;
        document.getElementById('detailId').textContent = `ID: #${data.id}`;
        document.getElementById('detailKategori').textContent = data.kategori.nama_kategori;
        document.getElementById('detailSpesifikasi').textContent = data.spesifikasi;
        
        // Set kondisi badge
        const kondisiBadge = document.getElementById('detailKondisiBadge');
        const kondisiClasses = {
            'Baik': 'bg-success text-white',
            'Rusak Ringan': 'bg-warning text-dark',
            'Rusak Berat': 'bg-danger text-white'
        };
        kondisiBadge.className = 'status-badge ' + (kondisiClasses[data.kondisi] || 'bg-secondary text-white');
        kondisiBadge.textContent = data.kondisi;
        
        showModal.show();
    };

    // Edit Barang
    window.editBarang = function(data) {
        const form = document.getElementById('editBarangForm');
        form.action = `{{ route('barang.index') }}/${data.id}`;
        
        // Fill form fields
        form.querySelector('#edit_nama_barang').value = data.nama_barang;
        form.querySelector('#edit_kategori_id').value = data.kategori_id;
        form.querySelector('#edit_spesifikasi').value = data.spesifikasi;
        form.querySelector('#edit_kondisi').value = data.kondisi;
        
        editModal.show();
    };

    // Delete Barang
    window.deleteBarang = function(id, nama) {
        const form = document.getElementById('deleteBarangForm');
        form.action = `{{ route('barang.index') }}/${id}`;
        document.getElementById('deleteBarangName').textContent = `"${nama}"`;
        deleteModal.show();
    };

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover'
        });
    });

    // Handle form submissions with loading animation
    document.querySelectorAll('.modal form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                // Save original button content
                const originalContent = submitBtn.innerHTML;
                
                // Disable button and show loading animation
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                `;
                
                // Restore button state after form submission (for error cases)
                setTimeout(() => {
                    if (!form.classList.contains('submitted')) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;
                    }
                }, 5000);
            }
            form.classList.add('submitted');
        });
    });

    // Reset forms and buttons on modal hide
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                form.classList.remove('submitted');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    // Reset button content based on form type
                    if (form.id === 'deleteBarangForm') {
                        submitBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
                    } else if (form.id === 'editBarangForm') {
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Perubahan';
                    } else {
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan';
                    }
                }
            }
        });
    });
});
</script>
@endpush
@endsection
