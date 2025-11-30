<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('complaints.index');
    }

    public function create()
    {
        return view('complaints.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'category' => 'required|in:Infrastruktur,Layanan,Akademik,Fasilitas,Sumber Daya Manusia',
        ]);

        Complaint::create([
            'nama_lengkap' => $request->nama_lengkap,
            'description' => $request->description,
            'email' => $request->email,
            'category' => $request->category,
        ]);

        return redirect()->route('complaints.thank-you');
    }

    public function thankYou()
    {
        return view('complaints.thank-you');
    }
}
