<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteReport;
use Illuminate\Support\Facades\Auth;


class ResidentReportController extends Controller
{

    public function dashboard()
    {
        $residentId = Auth::id();

        $reports = WasteReport::where('resident_id', $residentId)
                    ->latest()
                    ->get();

        return view('resident.dashboard', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location'           => 'required|string',
            'latitude'           => 'required',
            'longitude'          => 'required',
            'report_date'        => 'required|date',
            'waste_type'         => 'required|string',
            'additional_details' => 'nullable|string|max:1000',
            'image'              => 'required|image|mimes:jpeg,png,jpg|max:10240', 
        ]);

        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return back()->withErrors(['image' => 'Please upload a valid image file.'])->withInput();
        }

        $imagePath = $request->file('image')->store('reports', 'public');

        WasteReport::create([
            'resident_id'        => Auth::id(),
            'location'           => $request->location,
            'latitude'           => $request->latitude,
            'longitude'          => $request->longitude,
            'report_date'        => $request->report_date,
            'waste_type'         => $request->waste_type,
            'additional_details' => $request->additional_details,
            'image_path'         => $imagePath,
            'status'             => 'pending',
        ]);

        return redirect()->route('resident.dashboard')->with('success', 'Report submitted successfully!');
    }

    /**
     * Mark a report as urgent (bin full)
     */
    public function markUrgent(Request $request, WasteReport $report)
    {
        // Verify ownership
        if ($report->resident_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
            }
            return back()->with('error', 'Unauthorized access.');
        }
        
        // Check if can be marked urgent
        if (!$report->canBeMarkedUrgent()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'This report cannot be marked as urgent at this time.'], 400);
            }
            return back()->with('error', 'This report cannot be marked as urgent at this time.');
        }
        
        // Get optional message
        $message = $request->input('message', 'My bin is full - needs urgent collection');
        
        // Mark as urgent
        if ($report->markAsUrgent($message)) {
            // Notify all admins about urgent report
            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\UrgentBinReport($report));
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Your bin has been flagged as urgent! Admins have been notified for priority collection.'
                ]);
            }
            return back()->with('success', 'Your bin has been flagged as urgent! Admins have been notified for priority collection.');
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Unable to mark as urgent. Please try again.'], 500);
        }
        return back()->with('error', 'Unable to mark as urgent. Please try again.');
    }
}