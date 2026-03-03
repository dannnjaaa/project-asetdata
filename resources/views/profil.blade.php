@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="relative h-32 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/20"></div>
        </div>
        
        <div class="relative px-6 pb-6">
            <div class="flex flex-col items-center -mt-16">
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white bg-white shadow-lg">
                        <img 
                            id="previewImage"
                            src="{{ optional($user)->foto ? url('storage/' . $user->foto) : asset('default.png') }}" 
                            alt="Foto Profil" 
                            class="w-full h-full object-cover"
                        >
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <h1 class="text-2xl font-bold text-gray-900">{{ optional($user)->name ?? 'Nama Pengguna' }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Member sejak {{ optional($user)->created_at?->format('d M Y') ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Photo Upload -->
       <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="logo-container text-center mb-4">
        <img src="{{ asset('images/adevlogo.webp') }}" alt="Adev Logo" class="logo" 
             style="width: 180px; height: auto; margin: 20px auto; display: block; object-fit: cover;" />
         </div>
            </div>
            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center">
                        <div class="space-y-2">
                            <div class="flex items-center justify-center">
                                <label for="foto" class="cursor-pointer group">
                                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center border-2 border-blue-100 group-hover:border-blue-200 transition-colors duration-150">
                                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label for="foto" class="text-sm font-medium text-blue-600 hover:text-blue-700 cursor-pointer">
                                    Pilih foto baru
                                </label>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG atau JPEG (Maks. 2MB)</p>
                            </div>
                        </div>
                        <input 
                            type="file" 
                            id="foto" 
                            name="foto" 
                            accept="image/*"
                            class="hidden"
                            onchange="previewFile(this)"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-200"
                    >
                        Simpan Foto
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column - Profile Details -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-6">Informasi Profil</h3>
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-900">
                            {{ optional($user)->name ?? '-' }}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-900">
                            {{ optional($user)->email ?? '-' }}
                        </div>
                    </div>
                    
                    @if(optional($user)->alamat)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-900">
                            {{ $user->alamat }}
                        </div>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <a href="{{ route('dashboard.user') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
