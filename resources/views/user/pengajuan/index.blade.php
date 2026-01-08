@extends('layouts.user')

@section('title','Daftar Pengajuan Saya')

@section('content')
<div class="space-y-6">
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold">Daftar Pengajuan Saya</h1>
                <p class="text-sm text-gray-600">Menampilkan semua pengajuan yang Anda buat.</p>
            </div>
            <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Pengajuan
            </a>
        </div>

        @if($pengajuans->isEmpty())
            <div class="text-center py-8">
                <p class="text-sm text-gray-600">Anda belum membuat pengajuan apapun.</p>
                <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center mt-2 text-sm text-blue-600 hover:text-blue-700">
                    Buat pengajuan baru
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($pengajuans as $p)
                <div class="py-4 flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-3 h-3 rounded-full mt-2 " style="background: {{ $p->status === 'pending' ? '#0ea5a4' : ($p->status === 'diterima' ? '#10b981' : '#ef4444') }}"></div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ optional($p->asset)->nama ?? 'Asset: #' . $p->asset_id }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($p->catatan, 200) }}</p>
                            <p class="text-xs text-gray-400 mt-1"><span class="time-local" data-timestamp="{{ $p->created_at->toIso8601String() }}">{{ $p->created_at->format('d M Y H:i') }}</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="mb-2">
                            <span class="px-3 py-1 text-xs rounded-full text-white" style="background: {{ $p->status === 'pending' ? '#0ea5a4' : ($p->status === 'diterima' ? '#10b981' : '#ef4444') }}">{{ ucfirst($p->status) }}</span>
                        </div>
                        <a href="{{ route('pengajuan.show', $p->id) }}" class="text-sm text-blue-600 hover:text-blue-700">Lihat</a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
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
        try{ const dt = new Date(iso); if(isNaN(dt.getTime())) return; const parts = fmt.formatToParts(dt); const m = {}; parts.forEach(p=>m[p.type]=p.value); el.textContent = `${m.day} ${m.month} ${m.year} ${m.hour}:${m.minute}`; }catch(e){console.warn(e)}
    });
});
</script>
@endpush
