<?php
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Role-aware dashboard entry. If authenticated, redirect based on role; otherwise to welcome
Route::get('/dashboard', [App\Http\Controllers\AuthController::class, 'homeRedirect'])->name('dashboard');
Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
// Admin-only resources
Route::middleware(['auth','is_admin'])->group(function () {
    Route::resource('kategori', KategoriController::class);
    Route::resource('lokasi', LokasiController::class);
    Route::resource('barang', App\Http\Controllers\BarangController::class);
    Route::resource('user', UserController::class);
    // asset resource: admin manages everything except viewing (index/show)
    Route::resource('asset', App\Http\Controllers\AssetController::class)->except(['index','show']);
});

// Add export route to admin group
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::post('/monitoring/export/{type}', [App\Http\Controllers\MonitoringController::class, 'export'])
        ->name('monitoring.export')
        ->where('type', 'excel|pdf');
});

// Asset listing and detail should be available to authenticated users (view-only)
Route::resource('asset', App\Http\Controllers\AssetController::class)->only(['index','show'])->middleware('auth');
// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboards (protected)
Route::middleware('auth')->group(function () {
    // Admin dashboard and related routes
    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/dashboard/admin', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.admin');
    });
    // User dashboard available at /dashboard/user or default /dashboard for non-admins
    Route::get('/dashboard/user', [AuthController::class, 'userDashboard'])->name('dashboard.user');
});
Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

// Protected resources
Route::middleware('auth')->group(function () {
    // Pengajuan - accessible by all authenticated users
    Route::resource('pengajuan', App\Http\Controllers\PengajuanController::class);
    Route::post('pengajuan/store-draft', [App\Http\Controllers\PengajuanController::class, 'storeDraft'])->name('pengajuan.storeDraft');
    Route::patch('pengajuan/{pengajuan}/confirm', [App\Http\Controllers\PengajuanController::class, 'confirm'])->name('pengajuan.confirm');
    Route::patch('pengajuan/{pengajuan}/reject', [App\Http\Controllers\PengajuanController::class, 'reject'])->name('pengajuan.reject');
    
    // Monitoring - different views for admin and regular users
    Route::get('/monitoring', [App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/export/{type}', [App\Http\Controllers\MonitoringController::class, 'export'])
        ->name('monitoring.export')
        ->where('type', 'excel|pdf');
});

