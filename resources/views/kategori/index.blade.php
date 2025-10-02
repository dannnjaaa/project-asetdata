
@extends('layouts.app')
@section('title', 'Data Kategori')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tags me-2 text-primary" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0">Data Kategori</h5>
                    </div>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" onclick="openCreateModal()">
                        <i class="fa fa-plus me-2"></i> Tambah Kategori
                    </button>
                    @endif
                </div>
                <div class="card-body">
                    @include('components.notification-modal')
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Nama Kategori</th>
                                    <th class="text-center" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori as $i => $k)
                                <tr>
                                    <td class="text-center">{{ $i+1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-light text-dark me-2" style="padding: 8px; border-radius: 8px;">
                                                <i class="fas fa-tag text-primary"></i>
                                            </span>
                                            <span style="font-weight: 500;">{{ $k->nama_kategori }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons d-flex justify-content-center gap-2">
                                            @if(auth()->check() && auth()->user()->role === 'admin')
                                            <button type="button" class="btn btn-warning btn-sm" 
                                                    onclick="openEditModal('{{ $k->id }}', '{{ $k->nama_kategori }}')" 
                                                    title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="openDeleteModal('{{ $k->id }}', '{{ $k->nama_kategori }}')" 
                                                    title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @else
                                            <small class="text-muted">-</small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <i class="fas fa-database mb-3"></i>
                                            <h6 class="mb-1">Belum Ada Data</h6>
                                            <p class="text-muted small mb-0">Silakan tambahkan kategori baru untuk memulai</p>
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
    </div>
</div>

<!-- Create Modal -->
<div class="modal" id="createKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-plus-circle text-primary me-2"></i>
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-tag text-primary"></i>
                            </span>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required 
                                   placeholder="Masukkan nama kategori...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit text-warning me-2"></i>
                    <h5 class="modal-title">Edit Kategori</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editKategoriForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="edit_nama_kategori" class="form-label">Nama Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-tag text-primary"></i>
                            </span>
                            <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal modal-blur fade" id="deleteKategoriModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-trash text-danger"></i>
                    Hapus Kategori
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
                
                <p class="mb-0">Apakah Anda yakin ingin menghapus kategori <strong id="deleteKategoriName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <form id="deleteKategoriForm" method="POST">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal instances
    const modals = {
        create: new bootstrap.Modal(document.getElementById('createKategoriModal')),
        edit: new bootstrap.Modal(document.getElementById('editKategoriModal')),
        delete: new bootstrap.Modal(document.getElementById('deleteKategoriModal'))
    };

    // Open Create Modal
    window.openCreateModal = function() {
        hideAllModals();
        requestAnimationFrame(() => {
            document.getElementById('nama_kategori').value = '';
            modals.create.show();
        });
    };

    // Open Edit Modal
    window.openEditModal = function(id, nama) {
        hideAllModals();
        requestAnimationFrame(() => {
            const form = document.getElementById('editKategoriForm');
            form.action = `{{ route('kategori.index') }}/${id}`;
            document.getElementById('edit_nama_kategori').value = nama;
            modals.edit.show();
        });
    };

    // Open Delete Modal
    window.openDeleteModal = function(id, nama) {
        hideAllModals();
        requestAnimationFrame(() => {
            const form = document.getElementById('deleteKategoriForm');
            form.action = `{{ route('kategori.index') }}/${id}`;
            document.getElementById('deleteKategoriName').textContent = `"${nama}"`;
            modals.delete.show();
        });
    };

    // Hide all modals
    function hideAllModals() {
        Object.values(modals).forEach(modal => {
            try {
                modal.hide();
            } catch (e) {}
        });
        
        // Clean up any stuck backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });
        
        // Reset body styles
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
    }

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

    // Handle modal events
    document.querySelectorAll('.modal').forEach(modalElement => {
        modalElement.addEventListener('shown.bs.modal', function() {
            // Ensure single backdrop
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach((backdrop, index) => {
                if (index > 0) backdrop.remove();
            });
        });

        modalElement.addEventListener('hide.bs.modal', function() {
            // Add fade out effect
            this.classList.add('hiding');
        });

        modalElement.addEventListener('hidden.bs.modal', function() {
            // Clean up after hide
            this.classList.remove('hiding');
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                form.classList.remove('submitted');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    // Reset button content based on form type
                    if (form.id === 'deleteKategoriForm') {
                        submitBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
                    } else if (form.id === 'editKategoriForm') {
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

<style>
/* Modal Optimizations */
.modal {
    backdrop-filter: none !important;
}

.modal-dialog {
    transform: none;
    transition: none;
}

.modal.hiding .modal-dialog {
    transition: transform 0.1s ease-out;
    transform: scale(0.95);
}

.modal-backdrop {
    transition: none !important;
}

.modal-content {
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000px;
    will-change: transform;
}

/* Prevent body shift */
body.modal-open {
    overflow-y: hidden !important;
    padding-right: 0 !important;
}

/* Prevent backdrop flicker */
.modal-backdrop + .modal-backdrop {
    display: none !important;
}
</style>
@endpush
@endsection
