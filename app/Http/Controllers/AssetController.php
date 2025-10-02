<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with(['kategori','lokasi'])->latest()->paginate(15);
        $kategoris = Kategori::all();
        $lokasis = Lokasi::all();
        return view('asset.index', compact('assets','kategoris','lokasis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'lokasi_id' => 'nullable|exists:lokasi,id',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto' => 'nullable|image|max:2048',
        ]);

        // generate kode unik
        $kode = 'AST-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        $data['kode'] = $kode;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('assets', 'public');
            $data['foto'] = $path;
        }

        $asset = Asset::create($data);

        // QR/barcode generation removed (not used in this deployment)

        return redirect()->route('asset.index')->with('success', 'Asset berhasil ditambahkan');
    }

    public function show(Asset $asset)
    {
        return view('asset.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $kategoris = Kategori::all();
        $lokasis = Lokasi::all();
        return view('asset.edit', compact('asset','kategoris','lokasis'));
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'lokasi_id' => 'nullable|exists:lokasi,id',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // delete old foto
            if ($asset->foto) {
                Storage::disk('public')->delete($asset->foto);
            }
            $path = $request->file('foto')->store('assets', 'public');
            $data['foto'] = $path;
        }

        $asset->update($data);

        // QR/barcode generation removed (not used)

        return redirect()->route('asset.index')->with('success', 'Asset berhasil diperbarui');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->foto) {
            Storage::disk('public')->delete($asset->foto);
        }
            // QR fields removed; only delete foto
        $asset->delete();
        return redirect()->route('asset.index')->with('success', 'Asset dihapus');
    }
}
