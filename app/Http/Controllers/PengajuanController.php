<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function index()
    {
        return view('pengajuan.index');
    }

    public function create(Request $request)
    {
        $step = $request->query('step', 1);
        $assetId = $request->query('asset_id');
        $kategoris = Kategori::all();
        $lokasis = Lokasi::all();
        
        // If step 2 (review) is requested but no draft exists, redirect to step 1
        if ($step == 2 && !session()->has('pengajuan_draft')) {
            return redirect()->route('pengajuan.create', ['step' => 1]);
        }
        
        return view('pengajuan.create', compact('step', 'assetId', 'kategoris', 'lokasis'));
    }

    public function storeDraft(Request $request)
    {
        $validated = $request->validate([
            'nama_asset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'lokasi_id' => 'required|exists:lokasi,id',
            'spesifikasi' => 'required|string',
            'alasan' => 'required|string',
            'urgensi' => 'required|in:rendah,sedang,tinggi',
        ]);

        // Store the draft in session
        session(['pengajuan_draft' => $validated]);

        // Return response based on request type
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Draft saved successfully',
                'redirect' => route('pengajuan.create', ['step' => 2])
            ]);
        }

        return redirect()->route('pengajuan.create', ['step' => 2])
            ->with('success', 'Silakan review pengajuan Anda');
    }

    /**
     * Display the specified pengajuan details for admin.
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with('asset')->findOrFail($id);
        return view('pengajuan.show', compact('pengajuan'));
    }

    /**
     * Confirm a pengajuan and change its status to complete.
     */
    public function confirm(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini tidak dapat dikonfirmasi karena statusnya bukan pending.');
        }

        $pengajuan->update([
            'status' => 'diterima'
        ]);

        return back()->with('success', 'Pengajuan berhasil dikonfirmasi.');
    }

    public function reject(Pengajuan $pengajuan, Request $request)
    {
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini tidak dapat ditolak karena statusnya bukan pending.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:255'
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->alasan_penolakan
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        // Check if pengajuan can be deleted
        if ($pengajuan->status === 'diterima') {
            return back()->with('error', 'Pengajuan yang sudah diterima tidak dapat dihapus.');
        }

        // Delete related asset if exists
        if ($pengajuan->asset) {
            $pengajuan->asset->delete();
        }

        $pengajuan->delete();

        return back()->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function store(Request $request)
    {
        try {
            // Get the draft from session
            $draft = session('pengajuan_draft');
            if (!$draft) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Data pengajuan tidak ditemukan. Silakan isi form kembali.'
                    ], 422);
                }
                return redirect()->route('pengajuan.create')
                    ->with('error', 'Data pengajuan tidak ditemukan. Silakan isi form kembali.');
            }

            // Generate unique kode for asset
            $kode = 'AS' . strtoupper(Str::random(6));
            while (Asset::where('kode', $kode)->exists()) {
                $kode = 'AS' . strtoupper(Str::random(6));
            }

            // Create the asset
            $asset = Asset::create([
                'kode' => $kode,
                'nama' => $draft['nama_asset'],
                'kategori_id' => $draft['kategori_id'],
                'lokasi_id' => $draft['lokasi_id'],
                'spesifikasi' => $draft['spesifikasi'],
                'kondisi' => 'Baik', // Default condition for new assets
                'status' => 'pending',
            ]);

            // Create the pengajuan
            $pengajuan = Pengajuan::create([
                'asset_id' => $asset->id,
                'user_id' => $request->user()->id,
                'nama_pengaju' => $request->user()->name,
                'catatan' => trim($draft['alasan'] . "\n\nTingkat urgensi: " . $draft['urgensi']),
                'status' => 'pending'
            ]);

            // Load relationships
            $asset->load('kategori', 'lokasi');
            
            // Save completed pengajuan data to session for step 3
            session([
                'pengajuan_complete' => [
                    'kode_asset' => $kode,
                    'nama_asset' => $draft['nama_asset'],
                    'kategori' => $asset->kategori->nama_kategori,
                    'lokasi' => $asset->lokasi->nama_lokasi,
                    'spesifikasi' => $draft['spesifikasi'],
                    'alasan' => $draft['alasan'],
                    'urgensi' => $draft['urgensi'],
                    'tanggal' => now()->format('d F Y H:i'),
                    'no_pengajuan' => 'PJN-' . now()->format('Ymd') . '-' . str_pad($pengajuan->id, 3, '0', STR_PAD_LEFT),
                    'nama_pengaju' => $request->user()->name
                ]
            ]);

            // Clear the draft from session
            session()->forget('pengajuan_draft');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Pengajuan berhasil disimpan',
                    'redirect' => route('pengajuan.create', ['step' => 3])
                ]);
            }

            return redirect()->route('pengajuan.create', ['step' => 3]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Pengajuan error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat menyimpan pengajuan. ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan.');
        }
    }


}
