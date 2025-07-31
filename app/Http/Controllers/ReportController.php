<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

class ReportController extends Controller
{
    /**
     * Show the form to submit a new waste bin report.
     */
    public function create()
    {
        return view('resident.submit-report');
    }

    /**
     * Handle the submission of a new waste bin report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location'     => 'required|string|max:255',
            'zone'         => 'required|string|max:255',
            'bin_status'   => 'required|string|in:Full,Overflowing,Damaged,Normal',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remarks'      => 'nullable|string|max:1000',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bin_reports', 'public');
        }

        Report::create([
            'user_id'   => Auth::id(),
            'location'  => $request->location,
            'zone'      => $request->zone,
            'bin_status'=> $request->bin_status,
            'image'     => $imagePath,
            'remarks'   => $request->remarks,
            'status'    => 'Pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('resident.dashboard')->with('success', 'Your bin report has been submitted!');
    }
}