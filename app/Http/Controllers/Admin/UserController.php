<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Faculty admin cannot access
        if ($user->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola users.');
        }

        if ($user->isSuperAdmin()) {
            $query = User::query();

            if ($request->has('search') && !empty($request->search)) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->has('role') && !empty($request->role)) {
                $query->where('role', $request->role);
            }

            $users = $query->paginate(10);
        } else {
            // Regular user can only see themselves
            $users = User::where('id', $user->id)->paginate(10);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Only super admin can create users
        if ($user->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah users.');
        }
        
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Only super admin can create users
        if ($user->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah users.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $currentUser = auth()->user();
        
        // Only super admin can view users
        if ($currentUser->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail users.');
        }
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();

        // Faculty admin cannot edit
        if ($currentUser->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit users.');
        }

        // Super admin can edit anyone, regular user can only edit self
        if (!$currentUser->isSuperAdmin() && $currentUser->id !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit informasi Anda sendiri.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Faculty admin cannot update
        if ($currentUser->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit users.');
        }

        // Super admin can update anyone, regular user can only update self
        if (!$currentUser->isSuperAdmin() && $currentUser->id !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit informasi Anda sendiri.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ];

        // Password fields only for super admin editing self or regular user
        if ($currentUser->isSuperAdmin() && $currentUser->id === $user->id || !$currentUser->isSuperAdmin()) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        // Role only for super admin
        if ($currentUser->isSuperAdmin()) {
            $rules['role'] = ['required', Rule::in(['user', 'admin'])];
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        
        // Only super admin can delete users
        if ($currentUser->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus users.');
        }
        
        // Prevent deleting self
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
