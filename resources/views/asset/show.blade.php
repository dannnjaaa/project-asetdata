@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $layout = $isAdmin ? 'layouts.app' : 'layouts.user';
    $fotoUrl = $asset->foto ? (request()->getSchemeAndHttpHost() . '/storage/' . $asset->foto) : asset('default.png');
@endphp

@extends($layout)
@section('title', 'Detail Asset')
@section('content')

<div class="asset-detail-page py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="modern-card">
                    <!-- Decorative Elements -->
                    <div class="decorative-shapes">
                        <div class="shape shape-1"></div>
                        <div class="shape shape-2"></div>
                        <div class="shape shape-3"></div>
                    </div>

                    <!-- Header with Back Button -->
                    <div class="card-header">
                        <a href="{{ route('asset.index') }}" class="btn-back">
                            <i class="fas fa-chevron-left"></i> Kembali
                        </a>
                        @if($isAdmin)
                            <a href="{{ route('asset.edit', $asset->id) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit Asset
                            </a>
                        @endif
                    </div>

                    <!-- Main Content -->
                    <div class="asset-profile">
                        <div class="asset-header">
                            <div class="asset-image-container">
                                <div class="image-decoration">
                                    <div class="decoration-corner top-left"></div>
                                    <div class="decoration-corner top-right"></div>
                                    <div class="decoration-corner bottom-left"></div>
                                    <div class="decoration-corner bottom-right"></div>
                                </div>
                                <img src="{{ $fotoUrl }}" alt="{{ $asset->nama }}" class="asset-image" />
                                @php
                                    $kondisiClass = match(strtolower($asset->kondisi ?? 'baik')) {
                                        'baik' => 'status-good',
                                        'rusak ringan' => 'status-warning',
                                        'rusak berat' => 'status-danger',
                                        default => 'status-default'
                                    };
                                    $kondisiIcon = match(strtolower($asset->kondisi ?? 'baik')) {
                                        'baik' => 'fa-check-circle',
                                        'rusak ringan' => 'fa-exclamation-circle',
                                        'rusak berat' => 'fa-times-circle',
                                        default => 'fa-info-circle'
                                    };
                                @endphp
                                <span class="status-badge {{ $kondisiClass }}">
                                    <i class="fas {{ $kondisiIcon }}"></i>
                                    {{ $asset->kondisi ?? 'Baik' }}
                                </span>
                            </div>
                            
                            <div class="asset-main-info">
                                <div class="title-section">
                                    <i class="fas fa-cube asset-icon"></i>
                                    <h2 class="asset-title">{{ $asset->nama }}</h2>
                                </div>
                                
                                <div class="asset-code">
                                    <i class="fas fa-barcode code-icon"></i>
                                    <span class="code-label">Kode Asset:</span>
                                    <span class="code-value">{{ $asset->kode }}</span>
                                </div>
                                
                                <div class="info-tags">
                                    @if($asset->kategori)
                                        <span class="info-tag">
                                            <i class="fas fa-tag"></i>
                                            {{ $asset->kategori->nama_kategori }}
                                        </span>
                                    @endif
                                    @if($asset->lokasi)
                                        <span class="info-tag">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $asset->lokasi->nama_lokasi }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="asset-details">
                            <div class="specs-section">
                                <div class="section-header">
                                    <i class="fas fa-clipboard-list section-icon"></i>
                                    <h6 class="section-title">Spesifikasi Asset</h6>
                                </div>
                                <div class="specs-content">
                                    <div class="specs-decoration">
                                        <i class="fas fa-cog specs-icon spinning"></i>
                                    </div>
                                    {{ $asset->spesifikasi ?? 'Tidak ada spesifikasi' }}
                                </div>
                            </div>

                            <div class="metadata-section">
                                <div class="metadata-item">
                                    <div class="metadata-icon-wrapper">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <div class="metadata-content">
                                        <span class="metadata-label">Tanggal Dibuat</span>
                                        <span class="metadata-value">{{ $asset->created_at?->format('d M Y') ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="metadata-item">
                                    <div class="metadata-icon-wrapper">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="metadata-content">
                                        <span class="metadata-label">Terakhir Diubah</span>
                                        <span class="metadata-value">{{ $asset->updated_at?->format('d M Y') ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.asset-detail-page {
    background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.modern-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-5px);
}

/* Image Section Styles */
.image-container {
    position: relative;
    background: linear-gradient(135deg, #f8faff 0%, #f0f4fc 100%);
    height: 100%;
    min-height: 400px;
    padding: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.asset-image {
    max-width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.image-wrapper:hover .asset-image {
    transform: scale(1.02);
}

/* Content Section Styles */
.content-wrapper {
    padding: 2.5rem;
}

.header-section {
    margin-bottom: 2rem;
}

.asset-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1f36;
    margin-bottom: 0.5rem;
}

.asset-code {
    color: #6b7280;
    font-size: 0.9rem;
}

.code-value {
    font-weight: 600;
    color: #4f46e5;
}

/* Info Tags */
.info-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border-radius: 20px;
    font-size: 0.9rem;
    color: #4b5563;
    transition: all 0.2s ease;
}

.info-tag:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

/* Status Badge */
.status-badge {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    z-index: 1;
}

.status-good {
    background: #dcfce7;
    color: #166534;
}

.status-warning {
    background: #fef3c7;
    color: #92400e;
}

.status-danger {
    background: #fee2e2;
    color: #991b1b;
}

/* Specifications Card */
.specs-card {
    background: #f8faff;
    border-radius: 15px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    transition: all 0.2s ease;
}

.specs-card:hover {
    background: #f3f4f6;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.specs-title {
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.specs-content {
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0;
}

/* Metadata Grid */
.metadata-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin: 1.5rem 0;
}

.metadata-item {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.metadata-item:hover {
    background: #f3f4f6;
    transform: translateY(-2px);
}

.metadata-label {
    display: block;
    font-size: 0.8rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.metadata-value {
    display: block;
    font-weight: 600;
    color: #374151;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-back, .btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-back {
    background: #f3f4f6;
    color: #4b5563;
}

.btn-back:hover {
    background: #e5e7eb;
    color: #374151;
}

.btn-edit {
    background: #4f46e5;
    color: white;
}

.btn-edit:hover {
    background: #4338ca;
    transform: translateY(-2px);
}

.asset-detail-page {
    background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.modern-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

/* Card Header */
.card-header {
    display: flex;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

/* Asset Profile Layout */
.asset-profile {
    padding: 2rem;
}

.asset-header {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

.asset-image-container {
    position: relative;
    flex: 0 0 300px;
}

.asset-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.asset-image:hover {
    transform: scale(1.02);
}

.asset-main-info {
    flex: 1;
    min-width: 0;
}

.asset-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1f36;
    margin-bottom: 0.5rem;
}

.asset-code {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.code-value {
    font-weight: 600;
    color: #4f46e5;
}

/* Info Tags */
.info-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.info-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border-radius: 20px;
    font-size: 0.9rem;
    color: #4b5563;
    transition: all 0.2s ease;
}

.info-tag:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

/* Status Badge */
.status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    z-index: 1;
}

.status-good {
    background: #dcfce7;
    color: #166534;
}

.status-warning {
    background: #fef3c7;
    color: #92400e;
}

.status-danger {
    background: #fee2e2;
    color: #991b1b;
}

/* Asset Details */
.asset-details {
    background: #f8faff;
    border-radius: 15px;
    padding: 2rem;
    margin-top: 1rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
}

.specs-section {
    margin-bottom: 2rem;
}

.specs-content {
    color: #6b7280;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Metadata Section */
.metadata-section {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.metadata-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.metadata-item i {
    font-size: 1.25rem;
    color: #4f46e5;
}

.metadata-content {
    display: flex;
    flex-direction: column;
}

.metadata-label {
    font-size: 0.8rem;
    color: #6b7280;
}

.metadata-value {
    font-weight: 600;
    color: #374151;
}

/* Action Buttons */
.btn-back, .btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 500;
    text-decoration: none;
}

.btn-back {
    background: #f3f4f6;
    color: #4b5563;
}

.btn-back:hover {
    background: #e5e7eb;
}

.btn-edit {
    background: #4f46e5;
    color: white;
}

.btn-edit:hover {
    background: #4338ca;
}

/* Decorative Shapes */
.decorative-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
    z-index: 1;
}

.shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.shape-1 {
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: linear-gradient(45deg, #4f46e5, #818cf8);
    animation: float 8s ease-in-out infinite;
}

.shape-2 {
    bottom: -30px;
    left: -30px;
    width: 150px;
    height: 150px;
    background: linear-gradient(45deg, #10b981, #34d399);
    animation: float 6s ease-in-out infinite reverse;
}

.shape-3 {
    top: 50%;
    right: 20%;
    width: 100px;
    height: 100px;
    background: linear-gradient(45deg, #f59e0b, #fbbf24);
    animation: float 7s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
    100% { transform: translateY(0) rotate(0deg); }
}

/* Image Decoration */
.image-decoration {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.decoration-corner {
    position: absolute;
    width: 30px;
    height: 30px;
    border: 3px solid #4f46e5;
    opacity: 0.5;
    transition: all 0.3s ease;
}

.top-left {
    top: -5px;
    left: -5px;
    border-right: 0;
    border-bottom: 0;
}

.top-right {
    top: -5px;
    right: -5px;
    border-left: 0;
    border-bottom: 0;
}

.bottom-left {
    bottom: -5px;
    left: -5px;
    border-right: 0;
    border-top: 0;
}

.bottom-right {
    bottom: -5px;
    right: -5px;
    border-left: 0;
    border-top: 0;
}

.asset-image-container:hover .decoration-corner {
    opacity: 1;
    transform: scale(1.1);
}

/* Enhanced Asset Title */
.title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.asset-icon {
    font-size: 2rem;
    color: #4f46e5;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Enhanced Code Display */
.code-icon {
    color: #4f46e5;
    margin-right: 0.5rem;
}

/* Section Styling */
.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.section-icon {
    font-size: 1.25rem;
    color: #4f46e5;
}

.specs-decoration {
    position: relative;
    float: right;
    margin-left: 1rem;
}

.specs-icon {
    font-size: 3rem;
    color: rgba(79, 70, 229, 0.1);
}

.spinning {
    animation: spin 10s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Enhanced Metadata Items */
.metadata-icon-wrapper {
    width: 40px;
    height: 40px;
    background: rgba(79, 70, 229, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.metadata-item:hover .metadata-icon-wrapper {
    background: rgba(79, 70, 229, 0.2);
    transform: scale(1.1);
}

.metadata-item i {
    font-size: 1.25rem;
    color: #4f46e5;
}

/* Status Badge Enhancement */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    font-weight: 600;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .asset-header {
        flex-direction: column;
    }

    .asset-image-container {
        flex: 0 0 auto;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    .shape-1, .shape-2 {
        opacity: 0.05;
    }
}

@media (max-width: 767.98px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
    }

    .asset-profile {
        padding: 1rem;
    }

    .asset-details {
        padding: 1.5rem;
    }

    .metadata-section {
        flex-direction: column;
        gap: 1rem;
    }

    .btn-back, .btn-edit {
        width: 100%;
        justify-content: center;
    }

    .shape-3 {
        display: none;
    }
}
</style>

@endsection
