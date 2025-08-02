<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'resident');

        // Search by name or email
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['active', 'blocked'])) {
            $query->where('status', $request->status);
        }

        // Filter by 2FA
        if ($request->has('two_factor') && in_array($request->two_factor, ['enabled', 'disabled'])) {
            $query->where('two_factor_enabled', $request->two_factor === 'enabled' ? 1 : 0);
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'resident') {
            return back()->withErrors(['Invalid user.']);
        }

        $user->status = $user->status === 1 ? 0 : 1;
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ($user->id === auth('admin')->id()) {
            return back()->withErrors(['You cannot delete your own account.']);
        }

        if ($user->role !== 'resident') {
            return back()->withErrors(['Invalid user.']);
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');
        $twoFactor = $request->input('two_factor');

        $users = User::query()
            ->where('role', 'resident')
            ->when($query, fn($q) =>
                $q->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%");
                }))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($twoFactor, fn($q) => $q->where('two_factor_enabled', $twoFactor === 'enabled'))
            ->latest()
            ->get();

        $html = view('admin.partials.resident_rows', compact('users'))->render();

        return response()->json(['html' => $html]);
    }
}
