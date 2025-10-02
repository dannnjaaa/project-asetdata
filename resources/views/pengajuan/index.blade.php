@extends('layouts.app')
@section('title', 'Data Pengajuan')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-file-alt me-2"></i>Data Pengajuan
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                <div>
                    Menampilkan 200 pengajuan terbaru yang tersimpan dalam sistem.
                </div>
            </div>

            @php $pengajuans = \App\Models\Pengajuan::orderBy('id', 'asc')->limit(200)->get(); @endphp
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px">ID</th>
                            <th>Asset</th>
                            <th>Nama Pengaju</th>
                            <th>Catatan</th>
                            <th style="width: 100px">Status</th>
                            <th style="width: 150px">Waktu</th>
                            <th class="text-center" style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($pengajuans as $p)
                        <tr>
                            <td class="text-center">{{ $p->id }}</td>
                            <td>
                                <span class="fw-medium">{{ $p->asset_id }}</span>
                            </td>
                            <td>{{ $p->nama_pengaju }}</td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 300px;">
                                    {{ $p->catatan }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning',
                                        'diterima' => 'bg-success',
                                        'ditolak' => 'bg-danger',
                                    ][$p->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('pengajuan.show', $p->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($p->status === 'pending')
                                    <button type="button"
                                            class="btn btn-sm btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmModal"
                                            data-pengajuan-id="{{ $p->id }}"
                                            title="Konfirmasi Pengajuan">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal"
                                            data-pengajuan-id="{{ $p->id }}"
                                            title="Tolak Pengajuan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-pengajuan-id="{{ $p->id }}"
                                            title="Hapus Pengajuan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <div class="avatar avatar-lg bg-success bg-opacity-10 rounded-circle mx-auto mb-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h4 class="modal-title mb-2" id="confirmModalLabel">Konfirmasi Pengajuan</h4>
                    <p class="text-muted mb-0">Anda akan menyetujui pengajuan ini. Setelah disetujui, sistem akan memproses permintaan pengajuan.</p>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <form id="confirmForm" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-check me-2"></i>Setuju
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar avatar-lg bg-warning bg-opacity-10 rounded-circle mx-auto mb-3">
                        <i class="fas fa-exclamation-circle fa-2x text-warning"></i>
                    </div>
                    <h4 class="modal-title" id="rejectModalLabel">Tolak Pengajuan</h4>
                    <p class="text-muted">Harap berikan alasan penolakan yang jelas agar pengaju dapat memahami keputusan ini.</p>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-floating mb-3">
                        <textarea class="form-control border-0 bg-light" 
                                  id="alasan_penolakan" 
                                  name="alasan_penolakan" 
                                  style="height: 120px; resize: none;"
                                  placeholder="Masukkan alasan penolakan"
                                  required></textarea>
                        <label for="alasan_penolakan">Alasan Penolakan</label>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-ban me-2"></i>Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <div class="avatar avatar-lg bg-danger bg-opacity-10 rounded-circle mx-auto mb-3">
                        <i class="fas fa-trash-alt fa-2x text-danger"></i>
                    </div>
                    <h4 class="modal-title mb-2" id="deleteModalLabel">Hapus Pengajuan</h4>
                    <div class="alert alert-danger bg-danger bg-opacity-10 border-0 mb-3">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        <span class="text-danger">Peringatan: Tindakan ini tidak dapat dibatalkan!</span>
                    </div>
                    <p class="text-muted mb-0">
                        Apakah Anda yakin ingin menghapus pengajuan ini? 
                        Semua data terkait pengajuan ini akan dihapus secara permanen.
                    </p>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-trash-alt me-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modal .avatar {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal .form-control:focus {
    box-shadow: none;
    border-color: #dee2e6;
}

.modal .form-floating label {
    padding-left: 1rem;
}

.modal .form-floating textarea {
    padding: 1rem;
}

.modal .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    padding: 0.5rem 1.5rem;
}

.modal .alert {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
}

.modal-content {
    border-radius: 1rem;
    overflow: hidden;
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm Modal Handler
        const confirmModal = document.getElementById('confirmModal');
        confirmModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const pengajuanId = button.getAttribute('data-pengajuan-id');
            const form = document.getElementById('confirmForm');
            form.setAttribute('action', '{{ url("/pengajuan") }}/' + pengajuanId + '/confirm');
        });

        // Reject Modal Handler
        const rejectModal = document.getElementById('rejectModal');
        rejectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const pengajuanId = button.getAttribute('data-pengajuan-id');
            const form = document.getElementById('rejectForm');
            form.setAttribute('action', '{{ url("/pengajuan") }}/' + pengajuanId + '/reject');
        });

        // Delete Modal Handler
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const pengajuanId = button.getAttribute('data-pengajuan-id');
            const form = document.getElementById('deleteForm');
            form.setAttribute('action', '{{ url("/pengajuan") }}/' + pengajuanId);
        });

        // Clear reject modal textarea when closed
        rejectModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('alasan_penolakan').value = '';
        });
    });
</script>
@endpush

@endsection
