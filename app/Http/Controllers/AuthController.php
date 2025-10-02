<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function homeRedirect()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        }

        return redirect()->route('dashboard.user');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))){
            $request->session()->regenerate();
            $user = Auth::user();
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin');
            }
            return redirect()->route('dashboard.user');
        }

        return back()->withErrors(['email' => 'Kredensial tidak cocok'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }

    public function adminDashboard()
    {
        // simple authorization check
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
        $assetCount = \App\Models\Asset::count();
        $userCount = User::count();
        $pengajuanCount = \App\Models\Pengajuan::count();
        $monitoringCount = 0; // placeholder

        return view('dashboard', compact('assetCount','userCount','pengajuanCount','monitoringCount'));
    }

    public function userDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        // Get asset statistics
        $assetCount = \App\Models\Asset::count();
        $assetConditions = \App\Models\Asset::select('kondisi', \DB::raw('count(*) as total'))
            ->groupBy('kondisi')
            ->pluck('total', 'kondisi')
            ->toArray();
        
        // Get user's requests
        $userId = Auth::id();
        $userRequests = \App\Models\Pengajuan::where('user_id', $userId)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Get latest requests for this user
        $latestRequests = \App\Models\Pengajuan::where('user_id', $userId)
            ->with('asset')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'assetCount',
            'assetConditions',
            'userRequests',
            'latestRequests'
        ));
    }
}
