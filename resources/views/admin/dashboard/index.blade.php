@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="container-fluid">
    <!-- Urgent Matters Alert -->
    @if($urgentMatters->isNotEmpty())
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-1">Perhatian Diperlukan!</h5>
                <div class="row g-3 mt-2">
                    @foreach($urgentMatters as $matter)
                    <div class="col-md-4">
                        <div class="d-flex align-items-center bg-white rounded p-3 border border-{{ $matter['type'] }}">
                            <div class="flex-grow-1">
                                <p class="mb-1 text-{{ $matter['type'] }}">{{ $matter['message'] }}</p>
                                <a href="{{ $matter['action_url'] }}" class="btn btn-sm btn-{{ $matter['type'] }}">
                                    {{ $matter['action_text'] }} <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Welcome Banner -->
    <div class="card bg-warning bg-gradient text-dark mb-4 shadow-sm">
        <div class="card-body py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 text-dark">Ringkasan aktivitas dan statistik sistem manajemen aset</p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><i class="fas fa-calendar-alt me-2"></i>
                        <span class="js-local-datetime" data-timestamp="{{ now()->toIso8601String() }}" data-format="date-long">-</span>
                    </p>
                    <p class="mb-0"><i class="fas fa-clock me-2"></i>
                        <span class="js-local-datetime" data-timestamp="{{ now()->toIso8601String() }}" data-format="time-short">-</span>
                        <small class="text-muted ms-2 js-timezone"></small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <!-- Asset Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10">
                                <i class="fas fa-boxes fa-lg text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-1">{{ $totalAssets ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Aset</p>
                        </div>
                    </div>
                    <div class="progress mb-2" style="height: 4px;">
                        @php
                            $goodPercent = $totalAssets > 0 ? ($assetBaik / $totalAssets) * 100 : 0;
                            $minorDamagePercent = $totalAssets > 0 ? ($assetRusakRingan / $totalAssets) * 100 : 0;
                            $majorDamagePercent = $totalAssets > 0 ? ($assetRusakBerat / $totalAssets) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $goodPercent }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $minorDamagePercent }}%"></div>
                        <div class="progress-bar bg-danger" style="width: {{ $majorDamagePercent }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>{{ $assetBaik ?? 0 }} Baik</span>
                        <span>{{ $assetRusakRingan ?? 0 }} Rusak Ringan</span>
                        <span>{{ $assetRusakBerat ?? 0 }} Rusak Berat</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm rounded-circle bg-info bg-opacity-10">
                                <i class="fas fa-file-alt fa-lg text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-1">{{ $totalRequests ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Pengajuan</p>
                        </div>
                    </div>
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-warning">{{ $pendingRequests ?? 0 }}</h5>
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-success">{{ $approvedRequests ?? 0 }}</h5>
                                    <i class="fas fa-check text-success"></i>
                                </div>
                                <small class="text-muted">Diterima</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-danger">{{ $rejectedRequests ?? 0 }}</h5>
                                    <i class="fas fa-times text-danger"></i>
                                </div>
                                <small class="text-muted">Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10">
                                <i class="fas fa-tags fa-lg text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">Distribusi Kategori</h6>
                            <small class="text-muted">Top 3 Kategori</small>
                        </div>
                    </div>
                    @forelse($assetsByCategory->take(3) as $category)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center small mb-1">
                            <span>{{ $category->nama_kategori }}</span>
                            <span class="text-muted">{{ $category->assets_count }}</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ ($category->assets_count / $totalAssets) * 100 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted small mb-0">Belum ada data kategori</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Trend Analysis -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                <i class="fas fa-chart-line fa-lg text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">Tren Bulanan</h6>
                            <small class="text-muted">vs. Bulan Lalu</small>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="d-flex align-items-center">
                                    @if($assetTrend['percentage'] > 0)
                                        <i class="fas fa-arrow-up text-success me-1"></i>
                                    @elseif($assetTrend['percentage'] < 0)
                                        <i class="fas fa-arrow-down text-danger me-1"></i>
                                    @else
                                        <i class="fas fa-minus text-muted me-1"></i>
                                    @endif
                                    <span class="small {{ $assetTrend['percentage'] > 0 ? 'text-success' : ($assetTrend['percentage'] < 0 ? 'text-danger' : 'text-muted') }}">
                                        {{ abs(round($assetTrend['percentage'])) }}%
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted d-block">Asset Baru</small>
                                    <span class="fw-medium">{{ $assetTrend['current'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="d-flex align-items-center">
                                    @if($requestTrend['percentage'] > 0)
                                        <i class="fas fa-arrow-up text-success me-1"></i>
                                    @elseif($requestTrend['percentage'] < 0)
                                        <i class="fas fa-arrow-down text-danger me-1"></i>
                                    @else
                                        <i class="fas fa-minus text-muted me-1"></i>
                                    @endif
                                    <span class="small {{ $requestTrend['percentage'] > 0 ? 'text-success' : ($requestTrend['percentage'] < 0 ? 'text-danger' : 'text-muted') }}">
                                        {{ abs(round($requestTrend['percentage'])) }}%
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted d-block">Pengajuan</small>
                                    <span class="fw-medium">{{ $requestTrend['current'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Requests -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list-alt me-2"></i>Pengajuan Terbaru
                        </h5>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Pengaju</th>
                                    <th>Asset</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequests as $request)
                                <tr>
                                    <td class="ps-4">#{{ $request->id }}</td>
                                    <td>{{ $request->nama_pengaju }}</td>
                                    <td>{{ $request->asset->nama ?? '-' }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($request->status) {
                                                'pending' => 'warning',
                                                'diterima' => 'success',
                                                'ditolak' => 'danger',
                                                default => 'secondary'
                                            };
                                            $statusIcon = match($request->status) {
                                                'pending' => 'clock',
                                                'diterima' => 'check-circle',
                                                'ditolak' => 'times-circle',
                                                default => 'question-circle'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="js-local-date" data-timestamp="{{ $request->created_at->toIso8601String() }}">-</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('pengajuan.show', $request->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p class="mb-0">Belum ada pengajuan terbaru</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & System Stats -->
        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('asset.create') }}" class="btn btn-light border w-100 p-3 text-start h-100">
                                <i class="fas fa-plus-circle text-primary mb-2"></i>
                                <span class="d-block">Tambah Asset</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('kategori.index') }}" class="btn btn-light border w-100 p-3 text-start h-100">
                                <i class="fas fa-tags text-success mb-2"></i>
                                <span class="d-block">Kelola Kategori</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.index') }}" class="btn btn-light border w-100 p-3 text-start h-100">
                                <i class="fas fa-user-cog text-info mb-2"></i>
                                <span class="d-block">Kelola User</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('monitoring.index') }}" class="btn btn-light border w-100 p-3 text-start h-100">
                                <i class="fas fa-chart-line text-warning mb-2"></i>
                                <span class="d-block">Monitoring</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-heart me-2"></i>Kesehatan Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Asset Distribution -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Distribusi Kondisi Asset</h6>
                            <span class="badge bg-primary">{{ $totalAssets ?? 0 }} Total</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            @php
                                $percentageBaik = $totalAssets > 0 ? ($assetBaik / $totalAssets * 100) : 0;
                                $percentageRusakRingan = $totalAssets > 0 ? ($assetRusakRingan / $totalAssets * 100) : 0;
                                $percentageRusakBerat = $totalAssets > 0 ? ($assetRusakBerat / $totalAssets * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $percentageBaik }}%" 
                                 title="Baik: {{ $assetBaik }}">
                            </div>
                            <div class="progress-bar bg-warning" style="width: {{ $percentageRusakRingan }}%" 
                                 title="Rusak Ringan: {{ $assetRusakRingan }}">
                            </div>
                            <div class="progress-bar bg-danger" style="width: {{ $percentageRusakBerat }}%" 
                                 title="Rusak Berat: {{ $assetRusakBerat }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success">{{ number_format($percentageBaik, 1) }}% Baik</small>
                            <small class="text-warning">{{ number_format($percentageRusakRingan, 1) }}% Rusak Ringan</small>
                            <small class="text-danger">{{ number_format($percentageRusakBerat, 1) }}% Rusak Berat</small>
                        </div>
                    </div>

                    <!-- Recent System Activities -->
                    <h6 class="mb-3">Aktivitas Sistem Terkini</h6>
                    <div class="timeline-sm">
                        @forelse($recentActivities as $activity)
                        <div class="timeline-item pb-3">
                                @php
                                    $activityTs = is_object($activity['created_at'])
                                        ? $activity['created_at']->toIso8601String()
                                        : \Carbon\Carbon::parse($activity['created_at'])->toIso8601String();

                                    $borderColor = match($activity['type'] ?? 'default') {
                                        'asset' => 'border-success',
                                        'request' => 'border-primary',
                                        default => 'border-info'
                                    };

                                    $icon = match($activity['type'] ?? 'default') {
                                        'asset' => 'box',
                                        'request' => 'file-alt',
                                        default => 'circle'
                                    };
                                @endphp
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-{{ $icon }} text-muted me-2"></i>
                                <span class="text-muted small js-local-time" data-timestamp="{{ $activityTs }}">-</span>
                            </div>
                            <div class="timeline-body border-start border-2 {{ $borderColor }} ps-3">
                                <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                <p class="text-muted mb-0 small">{{ $activity['description'] }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-stream fa-2x text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">Belum ada aktivitas tercatat</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-sm .timeline-item {
    position: relative;
}

.timeline-sm .timeline-body {
    position: relative;
}
</style>
<script>
    (function(){
        function formatLocal(iso, format){
            try{
                const d = new Date(iso);
                if(isNaN(d)) return '-';
                if(format === 'date-long'){
                    return d.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                }
                if(format === 'time-short'){
                    return d.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' });
                }
                return d.toLocaleString();
            }catch(e){
                return '-';
            }
        }

        document.querySelectorAll('.js-local-datetime').forEach(function(el){
            const ts = el.getAttribute('data-timestamp');
            const fmt = el.getAttribute('data-format') || '';
            el.textContent = formatLocal(ts, fmt);
        });

        document.querySelectorAll('.js-local-date').forEach(function(el){
            const ts = el.getAttribute('data-timestamp');
            try{ el.textContent = new Date(ts).toLocaleDateString(); }catch(e){ el.textContent = '-'; }
        });

        document.querySelectorAll('.js-local-time').forEach(function(el){
            const ts = el.getAttribute('data-timestamp');
            try{ el.textContent = new Date(ts).toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' }); }catch(e){ el.textContent = '-'; }
        });

        // show timezone abbreviation if possible
        const tzEl = document.querySelector('.js-timezone');
        if(tzEl){
            try{
                const tz = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
                tzEl.textContent = tz ? tz : '';
            }catch(e){ /* ignore */ }
        }
    })();
</script>
@endsection