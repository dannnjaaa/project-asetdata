<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Kategori;
use App\Exports\AssetExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// facades for Excel and PDF are used conditionally at runtime to avoid fatal errors

class MonitoringController extends Controller
{
    public function index()
    {
        // For non-admin users, show the regular monitoring view
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return view('monitoring.index');
        }

        // Get asset statistics
        $totalAsset = Asset::count();
        $assetBaik = Asset::where('kondisi', 'Baik')->count();
        $assetRusakRingan = Asset::where('kondisi', 'Rusak Ringan')->count();
        $assetRusakBerat = Asset::where('kondisi', 'Rusak Berat')->count();

        // Get latest assets with their relationships
        $latestAssets = Asset::with(['kategori', 'lokasi'])
                            ->latest()
                            ->paginate(10);

        // Get category distribution
        $kategoriDistribution = Kategori::withCount('assets')
                                      ->having('assets_count', '>', 0)
                                      ->get();

        // Get latest activities (example implementation)
        $latestActivities = collect([
            (object)[
                'title' => 'Asset Baru Ditambahkan',
                'description' => 'Admin menambahkan asset baru ke sistem',
                'created_at' => now()->subHours(2)
            ],
            (object)[
                'title' => 'Pembaruan Status',
                'description' => 'Status asset ID#123 diubah menjadi Rusak Ringan',
                'created_at' => now()->subHours(5)
            ],
            (object)[
                'title' => 'Pengajuan Baru',
                'description' => 'User mengajukan permintaan untuk asset baru',
                'created_at' => now()->subDays(1)
            ]
        ]);

        return view('admin.monitoring.index', compact(
            'totalAsset',
            'assetBaik',
            'assetRusakRingan',
            'assetRusakBerat',
            'latestAssets',
            'kategoriDistribution',
            'latestActivities'
        ));
    }

    public function export($type)
    {
        try {
            // Verify user is admin
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return back()->with('error', 'Unauthorized access');
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            
            // Get assets data
            $assets = Asset::with(['kategori', 'lokasi'])->get();
            
            if ($assets->isEmpty()) {
                return back()->with('error', 'No data available to export');
            }
            
            if ($type === 'excel') {
                // If maatwebsite/excel is available, use it. Otherwise fall back to a simple CSV download.
                if (class_exists('Maatwebsite\\Excel\\Facades\\Excel')) {
                    return \Maatwebsite\Excel\Facades\Excel::download(new AssetExport, "assets_{$timestamp}.xlsx");
                }

                // CSV fallback
                $filename = "assets_{$timestamp}.csv";
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                ];

                $columns = ['Kode','Nama','Kategori','Lokasi','Spesifikasi','Kondisi'];

                $callback = function() use ($assets, $columns) {
                    $out = fopen('php://output', 'w');
                    // BOM for Excel compatibility (optional)
                    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
                    // Use semicolon as delimiter for better compatibility with Excel on some locales
                    fputcsv($out, $columns, ';');

                    foreach ($assets as $a) {
                        $row = [
                            $a->kode ?? '',
                            $a->nama ?? '',
                            optional($a->kategori)->nama_kategori ?? '',
                            optional($a->lokasi)->nama_lokasi ?? '',
                            $a->spesifikasi ?? '',
                            $a->kondisi ?? '',
                        ];
                        fputcsv($out, $row, ';');
                    }

                    fclose($out);
                };

                return response()->stream($callback, 200, $headers);
            }

            if ($type === 'pdf') {
                if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.assets-pdf', compact('assets'));
                    return $pdf->download("assets_{$timestamp}.pdf");
                }

                // Fallback: render a printable HTML page so admin can use browser Print -> Save as PDF
                return response()->view('exports.assets-print', compact('assets', 'timestamp'))
                                 ->header('Content-Type', 'text/html');
            }

            return back()->with('error', 'Invalid export type');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export data. Please try again.');
        }
    }
}
