@extends('layouts.app')
@section('title', 'Monitoring Asset')
@section('content')
<div class="container-fluid">
    <!-- Overview Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Assets Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-boxes fa-fw fa-lg text-primary"></i>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Menu Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('asset.index') }}">Lihat Semua Asset</a></li>
                                    <li><a class="dropdown-item" href="{{ route('asset.create') }}">Tambah Asset Baru</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $totalAsset ?? 0 }}</h3>
                    <p class="text-muted mb-0">Total Asset</p>
                </div>
            </div>
        </div>

        <!-- Assets in Good Condition -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-check-circle fa-fw fa-lg text-success"></i>
                        </div>
                        <span class="badge bg-success">{{ number_format(($assetBaik ?? 0) / max(($totalAsset ?? 1), 1) * 100, 1) }}%</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $assetBaik ?? 0 }}</h3>
                    <p class="text-muted mb-0">Kondisi Baik</p>
                </div>
            </div>
        </div>

        <!-- Assets with Minor Damage -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-exclamation-triangle fa-fw fa-lg text-warning"></i>
                        </div>
                        <span class="badge bg-warning">{{ number_format(($assetRusakRingan ?? 0) / max(($totalAsset ?? 1), 1) * 100, 1) }}%</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $assetRusakRingan ?? 0 }}</h3>
                    <p class="text-muted mb-0">Rusak Ringan</p>
                </div>
            </div>
        </div>

        <!-- Assets with Major Damage -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="fas fa-times-circle fa-fw fa-lg text-danger"></i>
                        </div>
                        <span class="badge bg-danger">{{ number_format(($assetRusakBerat ?? 0) / max(($totalAsset ?? 1), 1) * 100, 1) }}%</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $assetRusakBerat ?? 0 }}</h3>
                    <p class="text-muted mb-0">Rusak Berat</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Asset Status Table -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>Status Asset Terkini
                            </h5>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown">
                                <style>
                                    .export-btn {
                                        background: linear-gradient(135deg, #2c3e50, #34495e);
                                        border: none;
                                        color: white;
                                        padding: 0.5rem 1.2rem;
                                        font-weight: 500;
                                        letter-spacing: 0.3px;
                                        transition: all 0.3s ease;
                                    }
                                    .export-btn:hover {
                                        transform: translateY(-2px);
                                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                        background: linear-gradient(135deg, #34495e, #2c3e50);
                                    }
                                    .export-btn .icon {
                                        transition: transform 0.3s ease;
                                        display: inline-block;
                                    }
                                    .export-btn:hover .icon {
                                        transform: translateY(-2px);
                                    }
                                </style>
                                <button class="btn btn-sm dropdown-toggle shadow-sm export-btn" 
                                        type="button" 
                                        data-bs-toggle="dropdown">
                                    <span class="icon">
                                        <i class="fas fa-download me-2 fa-fw"></i>
                                    </span>
                                    Export Data
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                    style="border: none; border-radius: 0.5rem; margin-top: 0.5rem;">
                                    <li>
                                        <form action="{{ route('monitoring.export', ['type' => 'excel']) }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 px-4 d-flex align-items-center">
                                                <span class="icon-circle p-2 me-3 rounded-circle" 
                                                      style="background: rgba(40, 167, 69, 0.1);">
                                                    <i class="fas fa-file-excel" style="color: #28a745;"></i>
                                                </span>
                                                <div>
                                                    <span class="fw-medium d-block" style="color: #2c3e50;">Export ke Excel</span>
                                                    <small class="text-muted">Download dalam format XLSX</small>
                                                </div>
                                            </button>
                                        </form>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <form action="{{ route('monitoring.export', ['type' => 'pdf']) }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 px-4 d-flex align-items-center">
                                                <span class="icon-circle p-2 me-3 rounded-circle"
                                                      style="background: rgba(220, 53, 69, 0.1);">
                                                    <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                                                </span>
                                                <div>
                                                    <span class="fw-medium d-block" style="color: #2c3e50;">Export ke PDF</span>
                                                    <small class="text-muted">Download dalam format PDF</small>
                                                </div>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Asset</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestAssets ?? [] as $asset)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                @if($asset->foto)
                                                    <img src="{{ asset('storage/' . $asset->foto) }}" 
                                                         alt="{{ $asset->nama }}"
                                                         class="rounded"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="rounded bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box text-secondary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ms-3">
                                                <p class="fw-medium mb-0">{{ $asset->nama }}</p>
                                                <small class="text-muted">{{ $asset->kode }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ optional($asset->kategori)->nama_kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-secondary me-1"></i>
                                        {{ optional($asset->lokasi)->nama_lokasi ?? '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match(strtolower($asset->kondisi ?? 'baik')) {
                                                'baik' => 'success',
                                                'rusak ringan' => 'warning',
                                                'rusak berat' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $asset->kondisi }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('asset.show', $asset->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('asset.edit', $asset->id) }}" 
                                           class="btn btn-sm btn-outline-secondary ms-1"
                                           data-bs-toggle="tooltip"
                                           title="Edit Asset">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p class="mb-0">Belum ada data asset yang tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(isset($latestAssets) && $latestAssets->hasPages())
                <div class="card-footer bg-white border-top-0">
                    {{ $latestAssets->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats and Filters -->
        <div class="col-xl-4">
            <!-- Category Distribution -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Distribusi Kategori
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($kategoriDistribution ?? [] as $kategori)
                    <div class="mb-3 last:mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ $kategori->nama_kategori }}</span>
                            <span class="badge bg-primary">{{ $kategori->assets_count }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ ($kategori->assets_count / max($totalAsset, 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-chart-pie fa-2x mb-3"></i>
                        <p class="mb-0">Belum ada data kategori</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Latest Activities -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($latestActivities ?? [] as $activity)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between mb-1">
                                <h6 class="mb-0">{{ $activity->title }}</h6>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 text-muted">{{ $activity->description }}</p>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-clock fa-2x mb-3"></i>
                            <p class="mb-0">Belum ada aktivitas tercatat</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection