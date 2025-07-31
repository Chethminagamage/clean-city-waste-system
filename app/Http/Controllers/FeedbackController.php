<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'feedback_type' => 'required|string|max:255',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'type' => $request->feedback_type,
            'submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }
}