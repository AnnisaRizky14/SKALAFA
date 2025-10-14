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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'nullable|email',
        ]);

        Complaint::create($request->all());

        return redirect()->route('complaints.thank-you');
    }

    public function thankYou()
    {
        return view('complaints.thank-you');
    }
}
