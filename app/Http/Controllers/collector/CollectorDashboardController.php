<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use App\Models\WasteReport;
use App\Notifications\ReportStatusUpdated;
use App\Repositories\WasteReportRepository;
use App\Services\GamificationService;

class CollectorDashboardController extends Controller
{
    protected $wasteReportRepository;
    protected $gamificationService;

    public function __construct(WasteReportRepository $wasteReportRepository, GamificationService $gamificationService)
    {
        $this->wasteReportRepository = $wasteReportRepository;
        $this->gamificationService = $gamificationService;
    }

    public function index()
    {
        $collector = Auth::guard('collector')->user();

        $activeReports = $this->wasteReportRepository->getActiveReportsForCollector($collector->id);
        $completedReports = $this->wasteReportRepository->getCompletedReportsForCollector($collector->id);
        $allAssignedReports = $this->wasteReportRepository->getAllAssignedReportsForCollector($collector->id);

        return view('collector.dashboard', [
            'collector'         => $collector,
            'activeReports'     => $activeReports,
            'completedReports'  => $completedReports,
            'assignedReports'   => $allAssignedReports, // Now includes both active and completed reports
            'collectorLat'      => $collector->latitude,
            'collectorLng'      => $collector->longitude,
        ]);
    }

    public function updateLocation(Request $request)
    {
        try {
            $validated = $request->validate([
                'latitude'  => ['required','numeric'],
                'longitude' => ['required','numeric'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->isJson() || $request->wantsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        }

        $user = Auth::guard('collector')->user();
        $user->latitude  = (float) $request->latitude;
        $user->longitude = (float) $request->longitude;
        $user->location  = $request->input('location'); // nullable
        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Confirm assignment and change status to enroute
     */
    public function confirmAssignment($id)
    {
        $report = WasteReport::findOrFail($id);

        if ($report->collector_id !== Auth::guard('collector')->id()) {
            return back()->with('error', 'Unauthorized');
        }

        if ($report->status === WasteReport::ST_ASSIGNED) {
            $report->status = WasteReport::ST_ENROUTE;
            $report->save();

            if ($report->resident) {
                $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_ENROUTE));
            }

            return back()->with('success', 'Assignment confirmed. Resident has been notified that you are enroute.');
        }

        return back()->with('error', 'This report cannot be confirmed at this time.');
    }

    /**
     * Mark report as collected (transition from enroute to collected)
     */
    public function startWork($id)
    {
        $report = WasteReport::findOrFail($id);

        if ($report->collector_id !== Auth::guard('collector')->id()) {
            return back()->with('error', 'Unauthorized');
        }

        if ($report->status === WasteReport::ST_ASSIGNED) {
            $report->status = WasteReport::ST_ENROUTE;
            $report->save();

            // Send notification about status change
            if ($report->resident) {
                $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_ENROUTE));
            }

            return back()->with('success', 'Collection started successfully.');
        } elseif ($report->status === WasteReport::ST_ENROUTE) {
            $report->status = WasteReport::ST_COLLECTED;
            $report->collected_at = now();
            $report->save();

            // Send notification about status change
            if ($report->resident) {
                $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_COLLECTED));
            }

            return back()->with('success', 'Report marked as collected.');
        }

        return back()->with('error', 'This report cannot be started or completed at this time.');
    }

    /**
     * Mark report as collected with completion image (enhanced version)
     */
    public function completeWithImage(Request $request, $id)
    {
        $request->validate([
            'completion_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'completion_notes' => 'nullable|string|max:500'
        ]);

        $report = WasteReport::findOrFail($id);

        if ($report->collector_id !== Auth::guard('collector')->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($report->status !== WasteReport::ST_ENROUTE) {
            return response()->json(['error' => 'This report cannot be completed at this time.'], 400);
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('completion_image')) {
                $image = $request->file('completion_image');
                $imageName = 'completion_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('completion_images', $imageName, 'public');
            }

            // Update report status and completion details
            $report->update([
                'status' => WasteReport::ST_COLLECTED,
                'completion_image_path' => $imagePath,
                'completion_notes' => $request->completion_notes,
                'completion_image_uploaded_at' => now(),
                'collected_at' => now()
            ]);

            // Award points to resident for completed collection
            if ($report->resident) {
                try {
                    $this->gamificationService->awardPoints(
                        $report->resident,
                        'report_collected',
                        null,
                        'Earned points for completed waste collection',
                        [
                            'report_id' => $report->id,
                            'waste_type' => $report->waste_type,
                            'collection_date' => now()->toDateString()
                        ]
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to award collection points: ' . $e->getMessage());
                }

                // Send notification about status change
                $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_COLLECTED));
            }

            return response()->json([
                'success' => true,
                'message' => 'Report completed successfully with completion image!',
                'completion_image_url' => $imagePath ? Storage::url($imagePath) : null
            ]);

        } catch (\Exception $e) {
            \Log::error('Completion with image failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to complete report. Please try again.'], 500);
        }
    }

    /**
     * Get report details for the modal view
     */
    public function reportDetails($id)
    {
        try {
            $collectorId = Auth::guard('collector')->id();
            
            $report = WasteReport::with('resident')
                ->where('collector_id', $collectorId)
                ->where('id', $id)
                ->first();

            if (!$report) {
                return response()->json([
                    'error' => 'Report not found or you are not authorized to view this report'
                ], 404);
            }

            return response()->json([
                'id' => $report->id,
                'reference_code' => $report->reference_code ?? '#' . $report->id,
                'waste_type' => $report->waste_type,
                'location' => $report->location,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
                'status' => $report->status,
                'priority' => $report->priority ?? 'Normal',
                'description' => $report->additional_details, // Fixed field name
                'created_at' => $report->created_at->format('M d, Y h:i A'),
                'updated_at' => $report->updated_at->format('M d, Y h:i A'),
                'completion_image_path' => $report->completion_image_path,
                'completion_image_url' => $report->completion_image_path ? Storage::url($report->completion_image_path) : null,
                'completion_notes' => $report->completion_notes,
                'completion_image_uploaded_at' => $report->completion_image_uploaded_at ? $report->completion_image_uploaded_at->format('M d, Y h:i A') : null,
                'resident' => $report->resident ? [
                    'name' => $report->resident->name,
                    'email' => $report->resident->email,
                    'contact' => $report->resident->contact ?? 'Not provided'
                ] : null,
                'images' => $report->images ?? [], // If you have image attachments
                'additional_notes' => $report->additional_notes ?? null
            ]);
        } catch (\Exception $e) {
            \Log::error('Report details error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Unable to load report details. Please try again.'
            ], 500);
        }
    }

    /**
     * Show report details page (for direct access from notifications)
     */
    public function show($id)
    {
        $collector = Auth::user();
        
        // Find the report, ensuring it belongs to this collector
        $report = WasteReport::with('resident')
            ->where('id', $id)
            ->where('collector_id', $collector->id)
            ->first();

        if (!$report) {
            return redirect()->route('collector.dashboard')
                ->with('error', 'Report not found or you are not assigned to this report.');
        }

        return view('collector.report-details', compact('report'));
    }

    /**
     * Show report details page (alternative method name)
     */
    public function showReportDetails($id)
    {
        $report = WasteReport::with('resident')
            ->where('collector_id', Auth::guard('collector')->id())
            ->findOrFail($id);

        return view('collector.report-details', compact('report'));
    }

    /**
     * Show all reports page
     */
    public function allReports()
    {
        $collector = Auth::user();

        $assignedReports = WasteReport::with('resident')
            ->forCollector($collector->id)
            ->orderByDesc('created_at')
            ->get();

        return view('collector.all-reports', [
            'assignedReports' => $assignedReports,
            'collectorLat' => $collector->latitude,
            'collectorLng' => $collector->longitude,
        ]);
    }

    /**
     * Show completed reports page
     */
    public function completedReports()
    {
        $collector = Auth::user();

        $completedReports = WasteReport::with('resident')
            ->completedForCollector($collector->id)
            ->orderByDesc('updated_at')
            ->get();

        return view('collector.completed-reports', [
            'completedReports' => $completedReports,
            'collectorLat' => $collector->latitude,
            'collectorLng' => $collector->longitude,
        ]);
    }

    /**
     * Show collector profile page
     */
    public function profile()
    {
        $collector = Auth::user();

        // Get stats for the profile
        $allReports = WasteReport::forCollector($collector->id)->get();
        $completedReportsCount = WasteReport::completedForCollector($collector->id)->count();
        $activeReportsCount = WasteReport::activeForCollector($collector->id)->count();

        return view('collector.profile', [
            'totalReports' => $allReports->count(),
            'completedReports' => $completedReportsCount,
            'activeReports' => $activeReportsCount,
        ]);
    }

    /**
     * Update collector profile information
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::guard('collector')->id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::guard('collector')->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact = $request->phone;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update collector password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::guard('collector')->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Update collector profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        $user = Auth::guard('collector')->user();

        // Delete old profile image if exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store new profile image
        $path = $request->file('profile_image')->store('profile-pictures', 'public');
        
        // Update user profile
        $user->profile_image = $path;
        $user->save();

        return back()->with('success', 'Profile picture updated successfully.');
    }

    /**
     * Remove collector profile picture
     */
    public function removeProfilePicture()
    {
        $user = Auth::guard('collector')->user();

        // Delete profile image if exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Remove from user record
        $user->profile_image = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Profile picture removed successfully.']);
    }
}
