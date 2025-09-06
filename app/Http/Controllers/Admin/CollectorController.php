<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\CollectorAccountMail;
use Illuminate\Validation\Rule;
use App\Services\CollectorService;
use App\Http\Requests\Admin\CollectorStoreRequest;

class CollectorController extends Controller
{
    protected $collectorService;

    public function __construct(CollectorService $collectorService)
    {
        $this->collectorService = $collectorService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $collectors = $this->collectorService->getCollectorsWithSearch($search, 10);
        return view('admin.collectors', compact('collectors', 'search'));
    }

    public function store(CollectorStoreRequest $request)
    {
        $this->collectorService->createCollector($request->validated());
        return redirect()->route('admin.collectors')->with('success', 'Collector created and email sent!');
    }

    public function update(Request $request, $id)
    {
        $collector = User::where('role', 'collector')->findOrFail($id);
        
        // Validate with the same rules as the form request
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->ignore($collector->id),
            ],
            'contact'  => 'required|string|max:20',
            'location' => 'required|string|max:255',
        ]);
        
        $this->collectorService->updateCollector($collector, $validatedData);
        return redirect()->route('admin.collectors')->with('success', 'Collector updated successfully.');
    }

    public function toggleStatus($id)
    {
        $collector = User::where('role', 'collector')->findOrFail($id);
        $result = $this->collectorService->toggleCollectorStatus($collector);
        return redirect()->back()->with('success', $result['message']);
    }

    public function destroy($id)
    {
        $collector = User::where('role', 'collector')->findOrFail($id);
        $collector->delete();

        return redirect()->back()->with('success', 'Collector deleted!');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'location' => 'nullable|string|max:255'
        ]);

        $user = auth()->user();
        $user->latitude = (float) $request->latitude;
        $user->longitude = (float) $request->longitude;
        $user->location = $request->location;
        $user->save();

        return response()->json(['success' => true]);
    }
    
}