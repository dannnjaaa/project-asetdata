@extends('layouts.app')
@section('title', 'Edit Asset')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Asset</h5>
            <a href="{{ route('asset.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('asset.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" value="{{ old('kode', $asset->kode) }}" class="form-control" required>
                    <small class="text-muted">Masukkan kode unik untuk asset (bebas).</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $asset->nama) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-control">
                        <option value="">- Pilih -</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ $asset->kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <select name="lokasi_id" class="form-control">
                        <option value="">- Pilih -</option>
                        @foreach($lokasis as $l)
                            <option value="{{ $l->id }}" {{ $asset->lokasi_id == $l->id ? 'selected' : '' }}>{{ $l->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Spesifikasi</label>
                    <textarea name="spesifikasi" class="form-control">{{ old('spesifikasi', $asset->spesifikasi) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-control" required>
                        <option value="Baik" {{ $asset->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ $asset->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ $asset->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto (kosongkan jika tidak diubah)</label>
                    <input type="file" name="foto" class="form-control">
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
