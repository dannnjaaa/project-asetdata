<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Prepare urgent matters
        $urgentMatters = collect();

        // Check for assets needing attention
        $damagedAssets = Asset::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();
        if ($damagedAssets > 0) {
            $urgentMatters->push([
                'type' => 'warning',
                'message' => "{$damagedAssets} aset memerlukan perbaikan atau penggantian",
                'action_url' => route('monitoring.index'),
                'action_text' => 'Cek Kondisi'
            ]);
        }

        // Check for pending requests
        $pendingCount = Pengajuan::where('status', 'pending')->count();
        if ($pendingCount > 0) {
            $urgentMatters->push([
                'type' => 'info',
                'message' => "{$pendingCount} pengajuan menunggu persetujuan",
                'action_url' => route('pengajuan.index'),
                'action_text' => 'Proses Sekarang'
            ]);
        }

        // Asset Statistics
        $totalAssets = Asset::count();
        $assetBaik = Asset::where('kondisi', 'Baik')->count();
        $assetRusakRingan = Asset::where('kondisi', 'Rusak Ringan')->count();
        $assetRusakBerat = Asset::where('kondisi', 'Rusak Berat')->count();
        
        // Request Statistics
        $totalRequests = Pengajuan::count();
        $pendingRequests = Pengajuan::where('status', 'pending')->count();
        $approvedRequests = Pengajuan::where('status', 'diterima')->count();
        $rejectedRequests = Pengajuan::where('status', 'ditolak')->count();
        
        // Category Distribution (with asset count for each category)
        $assetsByCategory = Kategori::withCount('assets')
            ->orderByDesc('assets_count')
            ->get();

        // Monthly Trends
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        
        // Asset trends
        $currentMonthAssets = Asset::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        $lastMonthAssets = Asset::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        
        $assetTrend = [
            'current' => $currentMonthAssets,
            'percentage' => $lastMonthAssets > 0 
                ? (($currentMonthAssets - $lastMonthAssets) / $lastMonthAssets) * 100 
                : 0
        ];

        // Request trends
        $currentMonthRequests = Pengajuan::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        $lastMonthRequests = Pengajuan::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        
        $requestTrend = [
            'current' => $currentMonthRequests,
            'percentage' => $lastMonthRequests > 0 
                ? (($currentMonthRequests - $lastMonthRequests) / $lastMonthRequests) * 100 
                : 0
        ];

        // Recent Activities
        $recentActivities = collect();
        
        // Get recent assets
        $recentAssets = Asset::latest()
            ->take(3)
            ->get()
            ->map(function ($asset) {
                return [
                    'title' => 'Asset Baru: ' . $asset->nama,
                    'description' => "Asset {$asset->kode} telah ditambahkan",
                    'created_at' => $asset->created_at,
                    'type' => 'asset'
                ];
            });
        $recentActivities = $recentActivities->concat($recentAssets);

        // Get recent requests for the table
        $recentRequests = Pengajuan::with(['asset'])
            ->latest()
            ->take(5)
            ->get();

        // Create activities from the requests
        $requestActivities = $recentRequests->map(function ($request) {
            return [
                'title' => 'Pengajuan ' . ucfirst($request->status),
                'description' => "Pengajuan untuk " . ($request->asset ? $request->asset->nama : 'Asset Tidak Ditemukan'),
                'created_at' => $request->created_at,
                'type' => 'request'
            ];
        });
        
        $recentActivities = collect($recentActivities)->concat($requestActivities);
        
        // Sort activities by date and limit to 5
        $recentActivities = $recentActivities
            ->sortByDesc('created_at')
            ->take(5);

        return view('admin.dashboard.index', compact(
            'urgentMatters',
            'totalAssets',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests',
            'assetBaik',
            'assetRusakRingan',
            'assetRusakBerat',
            'assetsByCategory',
            'assetTrend',
            'requestTrend',
            'recentRequests',
            'recentActivities'
        ));
    }
}