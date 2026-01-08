<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PengajuanController extends Controller
{
    public function index()
    {
        // If admin, show the admin-style pengajuan list
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('pengajuan.index');
        }

        // For regular users, show only their pengajuan
        $pengajuans = Pengajuan::with('asset')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.pengajuan.index', compact('pengajuans'));
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
        // If there's already a draft foto saved in session, allow skipping file upload.
        $hasDraftFoto = session()->has('pengajuan_draft') && !empty(session('pengajuan_draft.foto'));
        $fotoRule = $hasDraftFoto ? 'nullable|image|max:5120' : 'required|image|max:5120';

        $validated = $request->validate([
            'nama_asset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'lokasi_id' => 'required|exists:lokasi,id',
            'spesifikasi' => 'required|string',
            'alasan' => 'required|string',
            'urgensi' => 'required|in:rendah,sedang,tinggi',
            'foto' => $fotoRule, // required unless a draft foto already exists
        ]);

        // Handle optional foto upload: store temporarily on public disk under pengajuan_tmp
        $draft = $validated;
        if ($request->hasFile('foto')) {
            try {
                $path = $request->file('foto')->store('pengajuan_tmp', 'public');
                $draft['foto'] = $path; // store relative path in session
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to store pengajuan foto draft: ' . $e->getMessage());
            }
        } else {
            // Preserve existing foto path in session if present
            $existing = session('pengajuan_draft', []);
            if (!empty($existing['foto'])) {
                $draft['foto'] = $existing['foto'];
            }
        }

        // Store the draft in session
        session(['pengajuan_draft' => $draft]);

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
        $pengajuan = Pengajuan::with(['asset.kategori','asset.lokasi','user'])->findOrFail($id);

        // Admins can view any pengajuan using the admin view
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('pengajuan.show', compact('pengajuan'));
        }

        // Regular users can only view their own pengajuan
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403);
        }

        return view('user.pengajuan.show', compact('pengajuan'));
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

            // If draft contained a foto path (temporary), move it to a permanent folder and save on pengajuan
            if (!empty($draft['foto']) && Storage::disk('public')->exists($draft['foto'])) {
                try {
                    $destDir = 'pengajuan/' . $pengajuan->id;
                    $destPath = $destDir . '/' . basename($draft['foto']);
                    Storage::disk('public')->makeDirectory($destDir);
                    Storage::disk('public')->move($draft['foto'], $destPath);
                    $pengajuan->foto = $destPath;
                    $pengajuan->save();
                    // Also attach foto to the created asset so it displays in the asset listing
                    try {
                        $asset->foto = $destPath;
                        $asset->save();
                    } catch (\Throwable $ee) {
                        \Illuminate\Support\Facades\Log::warning('Failed to attach foto to asset: ' . $ee->getMessage());
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to move pengajuan foto: ' . $e->getMessage());
                }
            }

            // Load relationships
            $asset->load('kategori', 'lokasi');
            
            // Save completed pengajuan data to session for step 3
            $complete = [
                    'kode_asset' => $kode,
                    'nama_asset' => $draft['nama_asset'],
                    'kategori' => $asset->kategori->nama_kategori,
                    'lokasi' => $asset->lokasi->nama_lokasi,
                    'spesifikasi' => $draft['spesifikasi'],
                    'alasan' => $draft['alasan'],
                    'urgensi' => $draft['urgensi'],
            'tanggal' => now()->format('d F Y H:i'),
            'tanggal_iso' => now()->toIso8601String(),
                    'no_pengajuan' => 'PJN-' . now()->format('Ymd') . '-' . str_pad($pengajuan->id, 3, '0', STR_PAD_LEFT),
                    'nama_pengaju' => $request->user()->name
            ];

            // If pengajuan has foto, include it in the complete data
            if (!empty($pengajuan->foto)) {
                $complete['foto'] = $pengajuan->foto;
            }

            session(['pengajuan_complete' => $complete]);

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

    /**
     * Export pengajuan list as Excel (XLSX) or CSV for admin.
     */
    public function export($format)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $pengajuans = Pengajuan::with(['asset','user'])->orderBy('created_at','desc')->get();

        if ($pengajuans->isEmpty()) {
            return back()->with('error', 'No pengajuan to export');
        }

        $timestamp = now()->format('Y-m-d_H-i-s');

        // If Maatwebsite Excel available, try to produce a styled XLSX via a simple array export
        if ($format === 'excel' && class_exists('Maatwebsite\\Excel\\Facades\\Excel')) {
            // Build array rows (headings + data)
            $rows = [];
            foreach ($pengajuans as $p) {
                $rows[] = [
                    $p->id,
                    optional($p->asset)->kode,
                    optional($p->asset)->nama,
                    $p->nama_pengaju ?? optional($p->user)->name,
                    $p->catatan,
                    ucfirst($p->status),
                    $p->created_at->format('d M Y H:i'),
                ];
            }

            $headings = ['ID','Kode Asset','Nama Asset','Nama Pengaju','Catatan','Status','Tanggal Pengajuan'];

            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PengajuanExport(array_merge([$headings], $rows)), "pengajuan_{$timestamp}.xlsx");
        }

        // CSV fallback
        $filename = "pengajuan_{$timestamp}.csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = ['ID','Kode Asset','Nama Asset','Nama Pengaju','Catatan','Status','Tanggal Pengajuan'];

        $callback = function() use ($pengajuans, $columns) {
            $out = fopen('php://output', 'w');
            // BOM for Excel compatibility (optional)
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            // Use comma delimiter so CSV is separated into columns in Excel
            fputcsv($out, $columns, ',');

            foreach ($pengajuans as $p) {
                $row = [
                    $p->id,
                    optional($p->asset)->kode ?? '',
                    optional($p->asset)->nama ?? '',
                    $p->nama_pengaju ?? optional($p->user)->name,
                    $p->catatan,
                    ucfirst($p->status),
                    $p->created_at->format('d M Y H:i'),
                ];
                fputcsv($out, $row, ',');
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }


}
