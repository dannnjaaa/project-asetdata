
@extends('layouts.app')
@section('title', 'Data Lokasi')
@section('content')
<div class="container-fluid">
    <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Lokasi</h5>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createLokasiModal">
                <i class="fa fa-plus me-2"></i>Tambah Lokasi
            </button>
            @endif
        </div>
        <div class="card-body">
            @include('components.notification-modal')
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Nama Divisi</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lokasi as $i => $l)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $l->nama_lokasi }}</td>
                            <td>{{ $l->nama_divisi }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Lihat Detail"
                                        onclick='showLokasi(@json($l))'>
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @if(auth()->check() && auth()->user()->role === 'admin')
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Lokasi"
                                        onclick='editLokasi(@json($l))'>
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus Lokasi"
                                        onclick='deleteLokasi({{ $l->id }}, "{{ $l->nama_lokasi }}")'>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data lokasi</td>
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
<div class="modal modal-blur fade" id="showLokasiModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-map-marker-alt text-primary"></i>
                    Detail Lokasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary p-3 text-white">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1" id="detailNamaLokasi"></h6>
                                <span class="text-muted small" id="detailNamaDivisi"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Nama Lokasi</label>
                            <div class="detail-value" id="detailNamaLokasiCopy"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Nama Divisi</label>
                            <div class="detail-value" id="detailNamaDivisiCopy"></div>
                        </div>
                    </div>
                </div>

                <style>
                    .detail-item {
                        background: #fff;
                        border: 1px solid rgba(0,0,0,.125);
                        border-radius: .25rem;
                        padding: 1rem;
                        margin-top: 1rem;
                    }
                    .detail-value {
                        font-weight: 500;
                        margin-top: .25rem;
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
<div class="modal modal-blur fade" id="editLokasiModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-edit text-warning"></i>
                    Edit Lokasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLokasiForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <div class="alert alert-info d-flex align-items-center mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Lengkapi informasi lokasi dan divisi dengan benar</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_nama_lokasi" class="form-label">
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                    Nama Lokasi
                                </label>
                                <input type="text" class="form-control" id="edit_nama_lokasi" name="nama_lokasi" required>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_nama_divisi" class="form-label">
                                    <i class="fas fa-building text-primary me-1"></i>
                                    Nama Divisi
                                </label>
                                <input type="text" class="form-control" id="edit_nama_divisi" name="nama_divisi" required>
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
<div class="modal modal-blur fade" id="deleteLokasiModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-trash text-danger"></i>
                    Hapus Lokasi
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
                
                <p class="mb-0">Apakah Anda yakin ingin menghapus lokasi <strong id="deleteLokasiName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <form id="deleteLokasiForm" method="POST">
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
<div class="modal modal-blur fade" id="createLokasiModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Lokasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lokasi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                        <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi</label>
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Modals
    const showModal = new bootstrap.Modal(document.getElementById('showLokasiModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    const editModal = new bootstrap.Modal(document.getElementById('editLokasiModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteLokasiModal'), {
        backdrop: 'static',
        keyboard: false
    });

    // Show Lokasi Details
    window.showLokasi = function(data) {
        // Set modal content for header card
        document.getElementById('detailNamaLokasi').textContent = data.nama_lokasi;
        document.getElementById('detailNamaDivisi').textContent = data.nama_divisi;
        
        // Set modal content for detail cards
        document.getElementById('detailNamaLokasiCopy').textContent = data.nama_lokasi;
        document.getElementById('detailNamaDivisiCopy').textContent = data.nama_divisi;
        
        showModal.show();
    };

    // Edit Lokasi
    window.editLokasi = function(data) {
        const form = document.getElementById('editLokasiForm');
        form.action = `{{ route('lokasi.index') }}/${data.id}`;
        
        // Fill form fields
        form.querySelector('#edit_nama_lokasi').value = data.nama_lokasi;
        form.querySelector('#edit_nama_divisi').value = data.nama_divisi;
        
        editModal.show();
    };

    // Delete Lokasi
    window.deleteLokasi = function(id, nama) {
        const form = document.getElementById('deleteLokasiForm');
        form.action = `{{ route('lokasi.index') }}/${id}`;
        document.getElementById('deleteLokasiName').textContent = `"${nama}"`;
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

    // Reset forms on modal hide and restore button states
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                form.classList.remove('submitted');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    if (form.id === 'deleteLokasiForm') {
                        submitBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
                    } else if (form.id === 'editLokasiForm') {
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Perubahan';
                    } else {
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan';
                    }
                }
            }
        });
    });
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    });

    // Auto close alerts after 3 seconds
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
});
</script>
@endpush
@endsection
