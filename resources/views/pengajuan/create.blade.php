@extends('layouts.user')
@section('title', 'Pengajuan Asset')
@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Pengajuan Asset Baru
            </h2>
            <p class="mt-1 text-gray-500">
                Isi formulir berikut untuk mengajukan permintaan asset baru
            </p>
        </div>

        <!-- Progress Tracker -->
        <div class="mb-8">
            <div class="overflow-hidden rounded-full bg-gray-200">
                <div class="h-2 rounded-full bg-blue-500" style="width: {{ ($step == 1 ? 33 : ($step == 2 ? 66 : 100)) }}%"></div>
            </div>
            <div class="mt-3 grid grid-cols-3 text-sm font-medium text-gray-600">
                <div class="{{ $step >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
                    <span class="mr-2">1.</span> Pengisian Form
                </div>
                <div class="text-center {{ $step >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
                    <span class="mr-2">2.</span> Review
                </div>
                <div class="text-right {{ $step == 3 ? 'text-blue-600' : 'text-gray-400' }}">
                    <span class="mr-2">3.</span> Selesai
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                @if($step == 1)
                <form action="{{ route('pengajuan.storeDraft') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Informasi Asset -->
                    <div class="bg-gray-50 p-6 rounded-lg space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Asset yang Diajukan</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="nama_asset" class="block text-sm font-medium text-gray-700">
                                    Nama Asset <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="nama_asset" id="nama_asset" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md h-10 px-4 @error('nama_asset') border-red-300 @enderror" 
                                           placeholder="Contoh: Laptop Dell XPS 13"
                                           value="{{ old('nama_asset') }}">
                                    @error('nama_asset')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select id="kategori_id" name="kategori_id" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md h-10 px-4 @error('kategori_id') border-red-300 @enderror">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris ?? [] as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="spesifikasi" class="block text-sm font-medium text-gray-700">
                                    Spesifikasi yang Dibutuhkan <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <textarea id="spesifikasi" name="spesifikasi" rows="4" 
                                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('spesifikasi') border-red-300 @enderror"
                                              placeholder="Jelaskan spesifikasi yang dibutuhkan secara detail">{{ old('spesifikasi') }}</textarea>
                                    @error('spesifikasi')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pengajuan -->
                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <h3 class="font-medium text-gray-900">Detail Pengajuan</h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="alasan" class="block text-sm font-medium text-gray-700">
                                    Alasan Pengajuan <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <textarea id="alasan" name="alasan" rows="3" 
                                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('alasan') border-red-300 @enderror"
                                              placeholder="Jelaskan mengapa Anda membutuhkan asset ini">{{ old('alasan') }}</textarea>
                                    @error('alasan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="lokasi_id" class="block text-sm font-medium text-gray-700">
                                    Lokasi Penempatan <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select id="lokasi_id" name="lokasi_id" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md h-10 px-4 @error('lokasi_id') border-red-300 @enderror">
                                        <option value="">Pilih Lokasi</option>
                                        @foreach($lokasis ?? [] as $lokasi)
                                            <option value="{{ $lokasi->id }}" {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                                {{ $lokasi->nama_lokasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lokasi_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="urgensi" class="block text-sm font-medium text-gray-700">
                                    Tingkat Urgensi <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select id="urgensi" name="urgensi" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md h-10 px-4 @error('urgensi') border-red-300 @enderror">
                                        <option value="">Pilih Tingkat Urgensi</option>
                                        <option value="rendah" {{ old('urgensi') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                        <option value="sedang" {{ old('urgensi') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                        <option value="tinggi" {{ old('urgensi') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    </select>
                                    @error('urgensi')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('pengajuan.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Lanjutkan ke Review
                            </button>
                        </div>
                    </div>
                </form>
                @elseif($step == 2)
                <div class="space-y-6">
                    <!-- Review Header -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Review Pengajuan Asset</h3>
                        <p class="text-sm text-gray-600 max-w-md mx-auto">
                            Silakan periksa kembali informasi pengajuan Anda sebelum mengirimkan.
                        </p>
                    </div>

                    <!-- Review Content -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Asset Information -->
                        <div class="p-6 border-b border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-4">Informasi Asset</h4>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Asset</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ session('pengajuan_draft.nama_asset') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ optional($kategoris->find(session('pengajuan_draft.kategori_id')))->nama_kategori }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Spesifikasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ session('pengajuan_draft.spesifikasi') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Request Details -->
                        <div class="p-6">
                            <h4 class="font-medium text-gray-900 mb-4">Detail Pengajuan</h4>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alasan Pengajuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ session('pengajuan_draft.alasan') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lokasi Penempatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ optional($lokasis->find(session('pengajuan_draft.lokasi_id')))->nama_lokasi }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tingkat Urgensi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                            session('pengajuan_draft.urgensi') === 'tinggi' ? 'bg-red-100 text-red-800' : 
                                            (session('pengajuan_draft.urgensi') === 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') 
                                        }}">
                                            {{ ucfirst(session('pengajuan_draft.urgensi')) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Confirmation Notice -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Konfirmasi Pengajuan</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Pastikan semua informasi yang Anda masukkan sudah benar. Pengajuan yang telah dikirim tidak dapat diubah.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4 pt-4">
                        <button type="button" onclick="window.location.href='{{ route('pengajuan.create', ['step' => 1]) }}'"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Kembali & Edit
                        </button>
                        <button type="button" onclick="submitPengajuan()"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Konfirmasi & Kirim
                        </button>
                    </div>
                    
                    <!-- Hidden Form for submission -->
                    <form id="pengajuanForm" action="{{ route('pengajuan.store') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
                @elseif($step == 2)
                <div class="text-center py-8 space-y-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-medium text-gray-900">Konfirmasi Pengajuan Asset</h3>
                        <p class="text-sm text-gray-600 max-w-md mx-auto">
                            Pastikan Anda telah mengisi semua informasi dengan benar. 
                            Setelah dikonfirmasi, pengajuan akan diproses oleh admin.
                        </p>
                    </div>
                    
                    <div class="flex justify-center gap-4 pt-4">
                        <a href="{{ route('pengajuan.create', ['step' => 1]) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Kembali & Edit
                        </a>
                        <form action="{{ route('pengajuan.store') }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Konfirmasi & Kirim
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @elseif($step == 3)
                <!-- Success Modal -->
                <div id="successModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6 animate-modal-up">
                            <div>
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Pengajuan Berhasil!
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Pengajuan asset Anda telah berhasil dikirim dan akan segera diproses.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <button type="button" onclick="hideModal()"
                                        class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Invoice/Receipt Style Card -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 max-w-2xl mx-auto">
                        <!-- Header -->
                        <div class="text-center border-b border-gray-200 pb-4">
                            <h3 class="text-2xl font-bold text-gray-900">Bukti Pengajuan Asset</h3>
                            <p class="text-sm text-gray-500 mt-1">No. {{ session('pengajuan_complete.no_pengajuan') }}</p>
                        </div>

                        <!-- Content -->
                        <div class="mt-6 space-y-6">
                            <!-- Requestor Info -->
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Diajukan Oleh:</p>
                                    <p class="font-medium text-gray-900">{{ session('pengajuan_complete.nama_pengaju') }}</p>
                                    <p class="text-gray-500">{{ session('pengajuan_complete.tanggal') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu Persetujuan
                                    </span>
                                </div>
                            </div>

                            <!-- Asset Details -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="font-medium text-gray-900 mb-4">Detail Asset</h4>
                                <dl class="grid grid-cols-1 gap-4 text-sm">
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Kode Asset:</dt>
                                        <dd class="col-span-2 text-gray-900">{{ session('pengajuan_complete.kode_asset') }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Nama Asset:</dt>
                                        <dd class="col-span-2 text-gray-900">{{ session('pengajuan_complete.nama_asset') }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Kategori:</dt>
                                        <dd class="col-span-2 text-gray-900">{{ session('pengajuan_complete.kategori') }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Lokasi:</dt>
                                        <dd class="col-span-2 text-gray-900">{{ session('pengajuan_complete.lokasi') }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Urgensi:</dt>
                                        <dd class="col-span-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                session('pengajuan_complete.urgensi') === 'tinggi' ? 'bg-red-100 text-red-800' : 
                                                (session('pengajuan_complete.urgensi') === 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') 
                                            }}">
                                                {{ ucfirst(session('pengajuan_complete.urgensi')) }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Spesifikasi & Alasan -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Spesifikasi</h4>
                                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ session('pengajuan_complete.spesifikasi') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Alasan Pengajuan</h4>
                                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ session('pengajuan_complete.alasan') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="text-sm text-gray-500">
                                    <p>* Pengajuan ini akan diproses dalam 1-3 hari kerja</p>
                                    <p>* Anda akan menerima notifikasi saat status pengajuan berubah</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('pengajuan.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Lihat Daftar Pengajuan
                        </a>
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak Bukti
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="mt-6">
            <div class="rounded-lg bg-blue-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Informasi Pengajuan
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Pengajuan akan diproses dalam waktu 3-5 hari kerja.</li>
                                <li>Pastikan semua informasi yang diisi sudah benar dan lengkap.</li>
                                <li>Anda dapat memantau status pengajuan di halaman daftar pengajuan.</li>
                                <li>Tingkat urgensi akan mempengaruhi prioritas pemrosesan pengajuan.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        @push('scripts')
        <style>
            @keyframes modalUp {
                0% {
                    opacity: 0;
                    transform: translate(0, 50px) scale(0.95);
                }
                100% {
                    opacity: 1;
                    transform: translate(0, 0) scale(1);
                }
            }
            .animate-modal-up {
                animation: modalUp 0.3s ease-out forwards;
            }
            @media print {
                body * {
                    visibility: hidden;
                }
                .bg-white.rounded-lg.shadow-lg, .bg-white.rounded-lg.shadow-lg * {
                    visibility: visible;
                }
                .bg-white.rounded-lg.shadow-lg {
                    position: absolute;
                    left: 0;
                    top: 0;
                }
                .no-print {
                    display: none !important;
                }
            }
        </style>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successModal = document.getElementById('successModal');
            
            // Show modal on page load for step 3
            if ({{ $step }} === 3) {
                showModal();
            }

            // Handle draft form submission (step 1)
            const draftForm = document.querySelector('form[action="{{ route('pengajuan.storeDraft') }}"]');
            if (draftForm) {
                draftForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            },
                            body: new FormData(this)
                        });

                        if (response.ok) {
                            window.location.href = "{{ route('pengajuan.create', ['step' => 2]) }}";
                        } else {
                            const data = await response.json();
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    const message = data.errors[field][0];
                                    const input = this.querySelector(`[name="${field}"]`);
                                    if (input) {
                                        input.classList.add('border-red-300');
                                        const errorDiv = document.createElement('p');
                                        errorDiv.className = 'mt-1 text-sm text-red-600';
                                        errorDiv.textContent = message;
                                        input.parentNode.appendChild(errorDiv);
                                    }
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            }

            function showModal() {
                if (successModal) {
                    successModal.classList.remove('hidden');
                }
            }

            window.hideModal = function() {
                if (successModal) {
                    successModal.classList.add('hidden');
                }
            };

            // Function to submit pengajuan (called from button onclick)
            window.submitPengajuan = async function() {
                const form = document.getElementById('pengajuanForm');
                const submitButton = document.querySelector('button[onclick="submitPengajuan()"]');
                
                try {
                    // Disable button and show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})  // Sending empty object since we use session data
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = "{{ route('pengajuan.create', ['step' => 3]) }}";
                        }
                    } else {
                        // Reset button state
                        submitButton.disabled = false;
                        submitButton.innerHTML = `
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Konfirmasi & Kirim
                        `;

                        // Show error message
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (data.error) {
                            errorMessage = data.error;
                        } else if (data.errors) {
                            errorMessage = Object.values(data.errors).join('\n');
                        }
                        
                        // Create and show error alert
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = `
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">${errorMessage}</span>
                        `;
                        document.body.appendChild(alertDiv);
                        
                        // Remove alert after 5 seconds
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 5000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Konfirmasi & Kirim
                    `;
                    
                    // Show error alert
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">Terjadi kesalahan pada server. Silakan coba lagi.</span>
                    `;
                    document.body.appendChild(alertDiv);
                    
                    // Remove alert after 5 seconds
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                }
            };

            function clearErrors() {
                document.querySelectorAll('.text-red-600').forEach(el => el.remove());
                document.querySelectorAll('.border-red-300').forEach(el => el.classList.remove('border-red-300'));
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                clearErrors();

                const url = form.action;
                const token = document.querySelector('input[name="_token"]').value;
                const formData = new FormData(form);

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (res.status === 201) {
                        // success
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        form.reset();
                    } else if (res.status === 422) {
                        const data = await res.json();
                        if (data.errors) {
                            Object.keys(data.errors).forEach(function (field) {
                                const el = document.querySelector('[name="' + field + '"]');
                                if (el) {
                                    el.classList.add('border-red-300');
                                    const p = document.createElement('p');
                                    p.className = 'mt-1 text-sm text-red-600';
                                    p.textContent = data.errors[field][0];
                                    el.parentNode.appendChild(p);
                                }
                            });
                        }
                    } else {
                        // fallback: navigate to server response
                        window.location = url;
                    }
                } catch (err) {
                    console.error(err);
                    window.location = url;
                }
            });

            closeBtn.addEventListener('click', function () {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            });
        });
        </script>
        @endpush

        @endsection
