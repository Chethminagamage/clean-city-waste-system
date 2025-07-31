<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteReport;
use Illuminate\Support\Facades\Auth;


class ResidentReportController extends Controller
{
    // Show Dashboard
    public function dashboard()
    {
        $residentId = Auth::id();

        $reports = WasteReport::where('resident_id', $residentId)
                    ->latest()
                    ->get();

        return view('resident.dashboard', compact('reports'));
    }

    // Store new report
    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'report_date' => 'required|date',
            'waste_type' => 'required|string',
            'additional_details' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // 10MB
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                $imagePath = $image->store('reports', 'public');
            } else {
                return back()->withErrors(['image' => 'The uploaded image is not valid.']);
            }
        }

        WasteReport::create([
            'resident_id' => Auth::id(),
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'report_date' => $request->report_date,
            'waste_type' => $request->waste_type,
            'additional_details' => $request->additional_details,
            'image_path' => $imagePath,
            'status' => 'Pending',
        ]);

        return redirect()->route('resident.dashboard')->with('success', 'Report submitted successfully!');
    }
}