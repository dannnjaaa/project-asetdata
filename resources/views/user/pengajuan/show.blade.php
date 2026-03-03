@extends('layouts.user')

@section('title','Detail Pengajuan')

@section('content')
<div class="space-y-6">
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold">Detail Pengajuan #{{ $pengajuan->id }}</h1>
                <p class="text-sm text-gray-600">Informasi lengkap tentang asset dan deskripsi pengajuan Anda.</p>
            </div>
            <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border rounded-xl text-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(!empty($pengajuan->foto))
                <div class="md:col-span-2">
                    <div class="mb-4 text-center">
                        <h3 class="text-sm font-medium mb-2">Foto Pengajuan</h3>
                       <div class="w-full overflow-hidden rounded-lg px-4 py-2">
                         <img src="{{ asset('storage/' . $pengajuan->foto) }}" alt="Foto Pengajuan" class="w-full max-h-[400px] object-contain mx-auto rounded-lg" style="max-width:100%; display:block;"/>
                    </div>

                    </div>
                </div>
            @endif
            <div class="border rounded-lg p-4">
                <h3 class="text-sm font-medium mb-3">Informasi Asset</h3>
                @if($pengajuan->asset)
                    <div class="space-y-2 text-sm text-gray-700">
                        <div><strong>Kode:</strong> <span class="text-muted">{{ $pengajuan->asset->kode }}</span></div>
                        <div><strong>Nama:</strong> {{ $pengajuan->asset->nama }}</div>
                        <div><strong>Kategori:</strong> {{ optional($pengajuan->asset->kategori)->nama_kategori ?? '-' }}</div>
                        <div><strong>Lokasi:</strong> {{ optional($pengajuan->asset->lokasi)->nama_lokasi ?? '-' }}</div>
                        <div><strong>Spesifikasi:</strong></div>
                        <div class="bg-gray-50 p-3 rounded">{{ $pengajuan->asset->spesifikasi ?? '-' }}</div>
                    </div>
                @else
                    <div class="text-sm text-gray-600">Asset terkait tidak ditemukan.</div>
                @endif
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="text-sm font-medium mb-3">Deskripsi Pengajuan</h3>
                <div class="text-sm text-gray-700">
                    <p><strong>Nama Pengajuan:</strong> {{ $pengajuan->nama_pengaju }}</p>
                    <p><strong>Tanggal:</strong> <span class="time-local" data-timestamp="{{ $pengajuan->created_at->toIso8601String() }}">{{ $pengajuan->created_at->format('d M Y H:i') }}</span></p>
                    <div class="mt-3 p-3 bg-gray-50 rounded">{{ $pengajuan->catatan }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const fmt = new Intl.DateTimeFormat(undefined, { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: false });
    document.querySelectorAll('.time-local').forEach(function(el){
        const iso = el.getAttribute('data-timestamp');
        if(!iso) return;
        try{
            const dt = new Date(iso);
            if(isNaN(dt.getTime())) return;
            const parts = fmt.formatToParts(dt);
            const mapping = {};
            parts.forEach(p => mapping[p.type] = p.value);
            el.textContent = `${mapping.day} ${mapping.month} ${mapping.year} ${mapping.hour}:${mapping.minute}`;
        }catch(e){ console.warn('time-local format failed', e); }
    });
});
</script>
@endpush
