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

class CollectorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $collectors = User::where('role', 'collector')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('location', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.collectors', compact('collectors', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'contact'  => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ]);

        $plainPassword = Str::random(10);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($plainPassword),
            'role'              => 'collector',
            'contact'           => $request->contact,
            'location'          => $request->location,
            'status'            => true, 
            'email_verified_at' => now(),
        ]);

        Mail::to($user->email)->send(new CollectorAccountMail(
            $user->name,
            $user->email,
            $plainPassword
        ));

        return redirect()->route('admin.collectors')->with('success', 'Collector created and email sent!');
    }

    public function update(Request $request, $id)
    {
        $collector = User::where('role', 'collector')->findOrFail($id);

        // ✅ Validate with unique rule excluding current user's email
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->ignore($collector->id),
            ],
            'contact'  => 'required|string|max:20',
            'location' => 'required|string|max:255',
        ], [
            'email.unique' => 'This email is already registered to another user.',
        ]);

        // ✅ Proceed with update
        $collector->name     = $request->name;
        $collector->email    = $request->email;
        $collector->contact  = $request->contact;
        $collector->location = $request->location;
        $collector->save();

        return redirect()->route('admin.collectors')->with('success', 'Collector updated successfully.');
    }

    public function toggleStatus($id)
    {
        $collector = User::where('role', 'collector')->findOrFail($id);

        // Toggle the status
        $collector->status = !$collector->status;
        $collector->save();

        $action = $collector->status ? 'unblocked' : 'blocked';

        return redirect()->back()->with('success', "Collector has been {$action} successfully.");
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
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $user = auth()->user();
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json(['success' => true]);
    }
    
}