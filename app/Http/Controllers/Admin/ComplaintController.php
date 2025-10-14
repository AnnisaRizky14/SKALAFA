<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::latest()->paginate(15);
        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $complaint->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()->route('admin.complaints.index')->with('success', 'Pengaduan berhasil dihapus!');
    }
}
