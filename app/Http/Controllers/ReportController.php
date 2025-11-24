<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:user,challenge,comment,interpretation',
            'target_id' => 'required|integer',
            'reason' => 'required|string',
            'details' => 'nullable|string|max:500',
        ]);

        Report::create([
            'reporter_user_id' => Auth::id(),
            'type' => $request->type,
            'target_id' => $request->target_id,
            'reason' => $request->reason,
            'details' => $request->details,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Report submitted successfully. Admins will review it shortly.');
    }
}
