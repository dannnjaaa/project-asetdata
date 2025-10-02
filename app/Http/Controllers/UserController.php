<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = User::query();
        $nameColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'username') ? 'username' : 'name';
        if ($q) {
            $query->where(function($sub) use ($q, $nameColumn) {
                $sub->where($nameColumn, 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('role', 'like', "%{$q}%");
            });
        }

        $users = $query->orderBy('id','desc')->paginate(15)->withQueryString();

        return view('user.index', compact('users','q'));
    }

    public function store(Request $request)
    {
        // debug log incoming data to help diagnose why 'user' role might not persist
        Log::info('UserController@store payload', $request->only(['name','email','role']));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        // Explicitly set 'username' to match database column and ensure role defaults to 'user'
        $data = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
        ];
        // set correct name column
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'username')) {
            $data['username'] = $request->name;
        } else {
            $data['name'] = $request->name;
        }

        User::create($data);

        return redirect()->route('user.index')->with('success', 'User ' . $request->name . ' berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'email' => $request->email,
            'role' => $request->role,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'username')) {
            $data['username'] = $request->name;
        } else {
            $data['name'] = $request->name;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User ' . $request->name . ' berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User ' . $userName . ' berhasil dihapus');
    }
}
