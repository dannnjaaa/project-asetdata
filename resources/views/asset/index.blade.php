@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $layout = $isAdmin ? 'layouts.app' : 'layouts.user';
    $totalAssets = isset($assets) && method_exists($assets, 'total') ? $assets->total() : \App\Models\Asset::count();
@endphp

@extends($layout)
@section('title', 'Data Asset')
@section('content')

@if($isAdmin)
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Asset</h5>
                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Asset</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="GET" action="{{ route('asset.index') }}" class="mb-3">
                    <div class="input-group">
                        <input type="search" name="q" class="form-control" placeholder="Cari kode asset..." value="{{ request('q') }}" aria-label="Cari kode asset">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                        <a href="{{ route('asset.index') }}" class="btn btn-outline-danger">Reset</a>
                    </div>
                </form>

                <style>
                    /* Inline, high-specificity rules to ensure uniform action button sizing */
                    .asset-action, .asset-action.btn, .asset-action.button {
                        width: 44px !important;
                        height: 44px !important;
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        padding: 0 !important;
                        border-radius: 8px !important;
                        line-height: 1 !important;
                    }
                    .asset-action i {
                        font-size: 16px !important;
                        line-height: 1 !important;
                    }
                </style>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:48px;">#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th style="width:90px;">Foto</th>
                                <th style="width:150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $a)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration + ($assets->currentPage()-1)*$assets->perPage() }}</td>
                                    <td>{{ $a->kode }}</td>
                                    <td class="fw-semibold">{{ $a->nama }}</td>
                                    <td>{{ optional($a->kategori)->nama_kategori ?? '-' }}</td>
                                    <td>{{ optional($a->lokasi)->nama_lokasi ?? '-' }}</td>
                                    <td>
                                        @php
                                            $fotoUrl = asset('default.png');
                                            if ($a->foto) {
                                                $path = $a->foto;
                                                if (\Illuminate\Support\Str::startsWith($path, ['http://','https://','data:'])) {
                                                    $fotoUrl = $path;
                                                } elseif (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
                                                    $fotoUrl = asset($path);
                                                } else {
                                                    $fotoUrl = asset('storage/' . ltrim($path,'/'));
                                                }
                                            }
                                        @endphp
                                        <div style="width:64px; height:64px; overflow:hidden; border-radius:8px;">
                                            <img src="{{ $fotoUrl }}" alt="foto" style="width:100%; height:100%; object-fit:cover;" onerror="this.onerror=null;this.src='{{ asset('default.png') }}'" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('asset.show', $a->id) }}" class="btn btn-sm btn-outline-primary asset-action" title="Lihat" aria-label="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('asset.edit', $a->id) }}" class="btn btn-sm btn-outline-warning asset-action" title="Edit" aria-label="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('asset.destroy', $a->id) }}" onsubmit="return confirm('Hapus asset ini?');" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger asset-action" title="Hapus" aria-label="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data asset.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>
    </div>

        <!-- Modal Tambah Asset (admin) -->
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="formTambahAsset" action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Asset Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Asset <span class="text-danger">*</span></label>
                                        <input type="text" name="kode" class="form-control" required />
                                        <small class="text-muted">Masukkan kode asset (ADEV-IT-LPT-0001)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Asset <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" required />
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kategori</label>
                                            <select name="kategori_id" class="form-control">
                                                <option value="">- Pilih Kategori -</option>
                                                @foreach($kategoris as $k)
                                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Lokasi</label>
                                            <select name="lokasi_id" class="form-control">
                                                <option value="">- Pilih Lokasi -</option>
                                                @foreach($lokasis as $l)
                                                    <option value="{{ $l->id }}">{{ $l->nama_lokasi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Spesifikasi</label>
                                        <textarea name="spesifikasi" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kondisi</label>
                                        <select name="kondisi" class="form-control" required>
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                            <option value="Rusak Berat">Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Preview Foto</label>
                                    <div id="modal_fotoPreview" class="border rounded p-2 text-center" style="min-height:180px; display:none;">
                                        <img id="modal_fotoPreviewImg" src="" alt="preview" style="max-width:100%; max-height:160px; object-fit:cover;" />
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label">Pilih Foto</label>
                                        <input id="modal_inputFoto" type="file" name="foto" accept="image/*" class="form-control" />
                                        <small class="text-muted">JPEG/PNG, maksimal 2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Asset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @else
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Data Asset</h1>
                        <p class="text-sm text-gray-600">Menampilkan semua asset yang tersedia.</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Total</div>
                        <div class="text-xl font-semibold">{{ $totalAssets }} unit</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 400'><rect width='100%' height='100%' fill='%23f3f4f6'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%239ca3af' font-family='Arial, Helvetica, sans-serif' font-size='28'>No image</text></svg>";
                        $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode($svg);
                    @endphp
                    @forelse($assets as $a)
                        @php
                            $fotoUrl = $placeholder;
                            if ($a->foto) {
                                $path = $a->foto;
                                if (\Illuminate\Support\Str::startsWith($path, ['http://','https://','data:'])) {
                                    $fotoUrl = $path;
                                } else {
                                    $fotoUrl = asset('storage/' . ltrim($path,'/'));
                                }
                            }
                        @endphp

                        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                            <div class="relative aspect-[4/3] mb-4 rounded-xl overflow-hidden bg-gray-100">
                                <img src="{{ $fotoUrl }}" alt="{{ $a->nama }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='{{ $placeholder }}'" />
                            </div>
                            <h3 class="font-semibold text-gray-900 text-lg leading-tight">{{ $a->nama }}</h3>
                            <div class="space-y-1.5 text-sm text-gray-600 mt-2">
                                @if($a->kategori)
                                    <div>{{ optional($a->kategori)->nama_kategori }}</div>
                                @endif
                                @if($a->lokasi)
                                    <div>{{ optional($a->lokasi)->nama_lokasi }}</div>
                                @endif
                            </div>
                            <div class="pt-3">
                                <a href="{{ route('asset.show', $a->id) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-200 font-medium text-sm">Lihat Detail</a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada data asset</h3>
                                <p class="text-gray-500">Asset akan muncul di sini setelah admin menambahkannya</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $assets->links() }}
        </div>
    </div>
@endif

@endsection

<!-- styles inlined above inside card-body to ensure they are applied -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Preview for modal add asset
    const input = document.getElementById('modal_inputFoto');
    const previewWrap = document.getElementById('modal_fotoPreview');
    const previewImg = document.getElementById('modal_fotoPreviewImg');
    const form = document.getElementById('formTambahAsset');

    if(input){
        input.addEventListener('change', function(e){
            const file = e.target.files[0];
            if(!file){ previewWrap.style.display = 'none'; previewImg.src = ''; return; }
            if(!file.type.startsWith('image/')){ alert('File harus berupa gambar'); input.value=''; return; }
            const reader = new FileReader();
            reader.onload = function(evt){ previewImg.src = evt.target.result; previewWrap.style.display = 'block'; }
            reader.readAsDataURL(file);
        });
    }

    // Reset preview when modal closed
    var modalEl = document.getElementById('modalTambah');
    if(modalEl){
        modalEl.addEventListener('hidden.bs.modal', function(){
            if(previewWrap){ previewWrap.style.display = 'none'; }
            if(previewImg){ previewImg.src = ''; }
            if(form){ form.reset(); }
        });
    }
});
</script>
@endpush