@extends('layouts.user')

@section('title','Dashboard User')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <section class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="absolute right-0 top-0 w-1/3 h-full opacity-10">
            <svg class="w-full h-full text-blue-600" fill="currentColor" viewBox="0 0 100 100">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>
        
        <div class="relative px-6 py-8 sm:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Halo, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-base text-gray-600">
                        Selamat datang di Panel Pengguna AsetData
                    </p>
                </div>
                <a href="{{ route('profil.show') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Assets Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-600">Total Asset</h3>
                    <div class="mt-1 flex items-baseline">
                        <div class="text-2xl font-bold text-gray-900">{{ $assetCount }}</div>
                        <div class="ml-2 text-sm text-gray-600">unit</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('asset.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                    Lihat semua asset
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Important Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:col-span-2">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Menu Utama</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('monitoring.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-150">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0 mr-4">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Monitor Asset</h4>
                        <p class="text-sm text-gray-600 mt-0.5">Pantau status dan kondisi asset</p>
                    </div>
                </a>

                <a href="{{ route('pengajuan.create') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-150">
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0 mr-4">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Pengajuan Asset</h4>
                        <p class="text-sm text-gray-600 mt-0.5">Ajukan permintaan asset baru</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Asset Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Asset Status Overview -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Status Asset</h3>
            
            <div class="space-y-4">
                <!-- Progress Bar for Asset Conditions -->
                @php
                    $total = array_sum($assetConditions);
                    $baik = $assetConditions['Baik'] ?? 0;
                    $rusakRingan = $assetConditions['Rusak Ringan'] ?? 0;
                    $rusakBerat = $assetConditions['Rusak Berat'] ?? 0;
                    
                    $baikPercent = $total > 0 ? ($baik / $total) * 100 : 0;
                    $rusakRinganPercent = $total > 0 ? ($rusakRingan / $total) * 100 : 0;
                    $rusakBeratPercent = $total > 0 ? ($rusakBerat / $total) * 100 : 0;
                @endphp

                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500" style="width: {{ $baikPercent }}%; float: left;"></div>
                    <div class="h-full bg-yellow-500" style="width: {{ $rusakRinganPercent }}%; float: left;"></div>
                    <div class="h-full bg-red-500" style="width: {{ $rusakBeratPercent }}%; float: left;"></div>
                </div>

                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-gray-600">Baik</span>
                        </div>
                        <p class="text-lg font-semibold mt-1">{{ $baik }}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <span class="text-gray-600">Rusak Ringan</span>
                        </div>
                        <p class="text-lg font-semibold mt-1">{{ $rusakRingan }}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span class="text-gray-600">Rusak Berat</span>
                        </div>
                        <p class="text-lg font-semibold mt-1">{{ $rusakBerat }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Your Requests Overview -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900">Pengajuan Anda</h3>
                <a href="{{ route('pengajuan.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                    Lihat semua
                </a>
            </div>

            @if($latestRequests->isEmpty())
                <div class="text-center py-8">
                    <p class="text-sm text-gray-600">Belum ada pengajuan</p>
                    <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center mt-2 text-sm text-blue-600 hover:text-blue-700">
                        Buat pengajuan baru
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    <!-- Request Status Summary -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-blue-600">Pending</span>
                            <p class="text-lg font-semibold mt-1">{{ $userRequests['pending'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <span class="text-sm text-green-600">Disetujui</span>
                            <p class="text-lg font-semibold mt-1">{{ $userRequests['approved'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg">
                            <span class="text-sm text-red-600">Ditolak</span>
                            <p class="text-lg font-semibold mt-1">{{ $userRequests['rejected'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Recent Requests List -->
                    <div class="border-t border-gray-100 mt-4 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Pengajuan Terbaru</h4>
                        <div class="space-y-3">
                            @foreach($latestRequests as $request)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div @class([
                                            'w-2 h-2 rounded-full',
                                            'bg-blue-500' => $request->status === 'pending',
                                            'bg-green-500' => $request->status === 'approved',
                                            'bg-red-500' => $request->status === 'rejected',
                                        ])></div>
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900">
                                                {{ $request->asset->nama ?? 'Asset Tidak Ditemukan' }}
                                            </p>
                                            <p class="text-gray-500">{{ $request->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <span @class([
                                        'text-xs px-2 py-1 rounded-full',
                                        'bg-blue-100 text-blue-700' => $request->status === 'pending',
                                        'bg-green-100 text-green-700' => $request->status === 'approved',
                                        'bg-red-100 text-red-700' => $request->status === 'rejected',
                                    ])>
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
