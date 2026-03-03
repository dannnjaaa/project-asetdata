@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-file-alt me-2"></i>Detail Pengajuan #{{ $pengajuan->id }}
            </h5>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @php
                    $fotoPath = $pengajuan->foto ?? null;
                    // Display kode: prefer related asset kode, otherwise generate PNJ-YYYYMMDD-###
                    $displayKode = null;
                    if (!empty(optional($pengajuan->asset)->kode)) {
                        $displayKode = $pengajuan->asset->kode;
                    } else {
                        $displayKode = 'PJN-' . $pengajuan->created_at->format('Ymd') . '-' . str_pad($pengajuan->id, 3, '0', STR_PAD_LEFT);
                    }
                @endphp
                @if($fotoPath)
                <div class="col-12 mb-3">
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $fotoPath) }}" alt="foto pengajuan" style="max-width:300px;border-radius:8px;">
                    </div>
                </div>
                @endif
                <div class="col-12 mb-3 text-center">
                    <h6 class="text-muted">No. {{ $displayKode }}</h6>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informasi Pengajuan
                            </h6>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted">ID Pengajuan</dt>
                                <dd class="col-sm-8 mb-3">
                                    <span class="badge bg-primary">#{{ $pengajuan->id }}</span>
                                </dd>

                                <dt class="col-sm-4 text-muted">Nama Pengaju</dt>
                                <dd class="col-sm-8 mb-3">
                                    <i class="fas fa-user me-1 text-primary"></i>
                                    {{ $pengajuan->nama_pengaju }}
                                </dd>

                                <dt class="col-sm-4 text-muted">Status</dt>
                                <dd class="col-sm-8 mb-3">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                        ][$pengajuan->status] ?? 'bg-secondary';
                                        $statusIcon = [
                                            'pending' => 'clock',
                                            'approved' => 'check-circle',
                                            'rejected' => 'times-circle',
                                        ][$pengajuan->status] ?? 'question-circle';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                        {{ ucfirst($pengajuan->status) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4 text-muted">Catatan</dt>
                                <dd class="col-sm-8 mb-3">
                                    <div class="border rounded p-2 bg-light">
                                        {{ $pengajuan->catatan ?: 'Tidak ada catatan' }}
                                    </div>
                                </dd>

                                <dt class="col-sm-4 text-muted">Waktu Pengajuan</dt>
                                <dd class="col-sm-8 mb-0">
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                    {{ $pengajuan->created_at->format('d M Y H:i') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-box me-2"></i>Asset Terkait
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($pengajuan->asset)
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Kode Asset</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <span class="badge bg-secondary">{{ $pengajuan->asset->kode }}</span>
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Nama Asset</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <span class="fw-medium">{{ $pengajuan->asset->nama }}</span>
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Kategori</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <i class="fas fa-tag me-1 text-primary"></i>
                                        {{ optional($pengajuan->asset->kategori)->nama_kategori ?: 'Tidak ada kategori' }}
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Lokasi</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                        {{ optional($pengajuan->asset->lokasi)->nama_lokasi ?: 'Tidak ada lokasi' }}
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Spesifikasi</dt>
                                    <dd class="col-sm-8">
                                        <div class="border rounded p-2 bg-light">
                                            {{ $pengajuan->asset->spesifikasi ?: 'Tidak ada spesifikasi' }}
                                        </div>
                                    </dd>
                                </dl>
                            @else
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Kode Asset</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <span class="badge bg-secondary">{{ 'PJN-' . $pengajuan->created_at->format('Ymd') . '-' . str_pad($pengajuan->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Nama Asset</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <span class="fw-medium">{{ $pengajuan->nama_asset ?? 'Tidak ada nama asset' }}</span>
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Kategori</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <i class="fas fa-tag me-1 text-primary"></i>
                                        Tidak ada kategori
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Lokasi</dt>
                                    <dd class="col-sm-8 mb-3">
                                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                        Tidak ada lokasi
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Spesifikasi</dt>
                                    <dd class="col-sm-8">
                                        <div class="border rounded p-2 bg-light">
                                            Tidak ada spesifikasi
                                        </div>
                                    </dd>
                                </dl>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
