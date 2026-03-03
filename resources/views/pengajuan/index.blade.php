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

            <div class="d-flex justify-content-end mb-3">
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">Export Data</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="{{ route('pengajuan.export', ['format' => 'excel']) }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="dropdown-item">Export ke Excel</button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('pengajuan.export', ['format' => 'pdf']) }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="dropdown-item">Export ke PDF</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            @php $pengajuans = \App\Models\Pengajuan::orderBy('id', 'asc')->limit(200)->get(); @endphp
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px">No</th>
                            <th>Asset / Foto</th>
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
                                <div class="d-flex align-items-center">
                                    @if(!empty($p->foto))
                                        <img src="{{ asset('storage/' . $p->foto) }}" alt="foto" style="max-width:64px; border-radius:6px; margin-right:8px;">
                                    @endif
                                    <div>
                                        <span class="fw-medium">{{ $p->nama_asset ?? optional($p->asset)->nama ?? ('Asset ' . ($p->asset_id ?? $p->id)) }}</span>
                                        @php
                                            if (!empty(optional($p->asset)->kode)) {
                                                $listKode = $p->asset->kode;
                                            } else {
                                                $listKode = 'PJN-' . $p->created_at->format('Ymd') . '-' . str_pad($p->id, 3, '0', STR_PAD_LEFT);
                                            }
                                        @endphp
                                        <div class="text-muted small">{{ $listKode }}</div>
                                    </div>
                                </div>
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
        // When confirm/reject/delete buttons are clicked, populate the modal forms with the pengajuan id
        const confirmModal = document.getElementById('confirmModal');
        const rejectModal = document.getElementById('rejectModal');
        const deleteModal = document.getElementById('deleteModal');

        function onShowModal(e) {
                const button = e.relatedTarget;
                if (!button) return;
                const id = button.getAttribute('data-pengajuan-id');
                if (!id) return;

                if (e.target === confirmModal) {
                        const form = confirmModal.querySelector('form');
                        form.action = '/pengajuan/' + id + '/confirm';
                }
                if (e.target === rejectModal) {
                        const form = rejectModal.querySelector('form');
                        form.action = '/pengajuan/' + id + '/reject';
                }
                if (e.target === deleteModal) {
                        const form = deleteModal.querySelector('form');
                        form.action = '/pengajuan/' + id;
                }
        }

        // Bootstrap 5 modal show event wiring (support both bootstrap and fallback)
        [confirmModal, rejectModal, deleteModal].forEach(modal => {
                if (!modal) return;
                modal.addEventListener('show.bs.modal', onShowModal);
                // Fallback: when using data-bs-target with no bootstrap JS, listen for click on buttons
                modal.addEventListener('beforeshow', onShowModal);
        });
});
</script>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengonfirmasi pengajuan ini?
            </div>
            <div class="modal-footer">
                <form method="POST" action="" id="confirmForm">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="rejectForm" action="">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="rejectForm" class="btn btn-danger">Tolak</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengajuan ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <form method="POST" id="deleteForm" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endpush
