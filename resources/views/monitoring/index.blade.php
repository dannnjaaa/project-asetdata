@extends('layouts.user')
@section('title', 'Monitor Asset')
@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Monitor Asset
                    </h2>
                    <p class="mt-1 text-gray-500">
                        Pantau status dan kondisi asset yang tersedia
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Asset Baik</p>
                        <h3 class="text-xl font-bold text-green-600 mt-1">{{ $assetBaik ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rusak Ringan</p>
                        <h3 class="text-xl font-bold text-yellow-600 mt-1">{{ $assetRusakRingan ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Danger Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rusak Berat</p>
                        <h3 class="text-xl font-bold text-red-600 mt-1">{{ $assetRusakBerat ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Search and Filter -->
            <div class="p-4 border-b border-gray-100">
                <div class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Cari asset..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <select class="form-select border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <select class="form-select border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Asset Grid -->
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($assets ?? [] as $asset)
                    <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ $asset->foto ? asset('storage/' . $asset->foto) : asset('img/default-asset.jpg') }}"
                                 alt="{{ $asset->nama }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $asset->nama }}</h3>
                                    <p class="text-sm text-gray-500">Kode: {{ $asset->kode }}</p>
                                </div>
                                @php
                                    $kondisiClass = match(strtolower($asset->kondisi ?? 'baik')) {
                                        'baik' => 'bg-green-100 text-green-800',
                                        'rusak ringan' => 'bg-yellow-100 text-yellow-800',
                                        'rusak berat' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kondisiClass }}">
                                    {{ $asset->kondisi }}
                                </span>
                            </div>
                            <dl class="mt-3 space-y-1">
                                <div class="text-sm text-gray-500">
                                    <dt class="inline">Kategori:</dt>
                                    <dd class="inline ml-1">{{ optional($asset->kategori)->nama_kategori ?? '-' }}</dd>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <dt class="inline">Lokasi:</dt>
                                    <dd class="inline ml-1">{{ optional($asset->lokasi)->nama_lokasi ?? '-' }}</dd>
                                </div>
                            </dl>
                            <div class="mt-4 flex justify-end">
                                <a href="{{ route('asset.show', $asset->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Lihat Detail
                                    <svg class="ml-1.5 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada asset</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada asset yang tersedia untuk ditampilkan.
                        </p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if(isset($assets) && $assets->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $assets->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
