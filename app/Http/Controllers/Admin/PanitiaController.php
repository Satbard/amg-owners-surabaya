<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PanitiaController extends Controller
{
    public function index()
    {
        $panitia = User::where('role', 'panitia')
            ->orderBy('name')
            ->get();

        return view('admin.panitia.index', compact('panitia'));
    }

    public function create()
    {
        return view('admin.panitia.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'password' => Hash::make($validated['password']),
            'role' => 'panitia',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Menambahkan panitia baru: {$user->name} ({$user->username})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('admin.panitia.index')
            ->with('success', "Panitia {$user->name} berhasil ditambahkan.");
    }

    public function destroy(Request $request, User $user)
    {
        // Prevent deleting non-panitia accounts or your own account.
        if ($user->role !== 'panitia') {
            abort(403, 'Hanya akun panitia yang dapat dihapus.');
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'username' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Menghapus panitia: {$user->name} ({$user->username})",
            'ip_address' => $request->ip(),
        ]);

        $user->delete();

        return redirect()
            ->route('admin.panitia.index')
            ->with('success', "Panitia {$user->name} berhasil dihapus.");
    }
}
