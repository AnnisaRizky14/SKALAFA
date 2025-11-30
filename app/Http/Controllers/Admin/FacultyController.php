<?php
// app/Http/Controllers/Admin/FacultyController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Faculty::query();

        // Faculty admin can only see their own faculty
        if ($user->isFacultyAdmin()) {
            $query->where('id', $user->faculty_id);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('short_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $faculties = $query->ordered()->paginate(10)->appends($request->query());
        return view('admin.faculties.index', compact('faculties'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Only super admin can create faculties
        if ($user->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah fakultas.');
        }
        
        return view('admin.faculties.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Only super admin can create faculties
        if ($user->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah fakultas.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
            'order' => 'integer|min:0'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->handleLogoUpload($request->file('logo'));
        }

        Faculty::create($validated);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function show(Faculty $faculty)
    {
        $user = auth()->user();
        
        // Faculty admin can only view their own faculty
        if ($user->isFacultyAdmin() && $faculty->id !== $user->faculty_id) {
            abort(403, 'Anda tidak memiliki akses ke fakultas ini.');
        }
        
        $stats = $faculty->getSatisfactionStats();
        $questionnaires = $faculty->questionnaires()->with('responses')->paginate(5);
        
        return view('admin.faculties.show', compact('faculty', 'stats', 'questionnaires'));
    }

    public function edit(Faculty $faculty)
    {
        $user = auth()->user();

        // Faculty admin can only edit their own faculty
        if ($user->isFacultyAdmin() && $faculty->id !== $user->faculty_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit fakultas ini.');
        }

        return view('admin.faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $user = auth()->user();

        // Faculty admin can only update their own faculty
        if ($user->isFacultyAdmin() && $faculty->id !== $user->faculty_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit fakultas ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'is_active' => 'boolean',
            'order' => 'integer|min:0'
        ]);

        $faculty->update($validated);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Faculty $faculty)
    {
        $user = auth()->user();
        
        // Only super admin can delete faculties
        if ($user->isFacultyAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus fakultas.');
        }
        
        // Check if faculty has questionnaires
        if ($faculty->questionnaires()->exists()) {
            return redirect()->route('admin.faculties.index')
                ->with('error', 'Fakultas tidak dapat dihapus karena masih memiliki kuisioner.');
        }

        // Delete logo file
        if ($faculty->logo && Storage::exists('images/faculties/' . $faculty->logo)) {
            Storage::delete('images/faculties/' . $faculty->logo);
        }

        $faculty->delete();

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil dihapus.');
    }

    private function handleLogoUpload($file)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'images/faculties/' . $filename;

        // Resize and optimize image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image = $image->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Save the image
        Storage::put($path, $image->encode());

        return $filename;
    }
}