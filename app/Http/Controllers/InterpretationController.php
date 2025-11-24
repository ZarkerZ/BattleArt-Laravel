<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Challenge;
use App\Models\Interpretation;
use App\Models\Notification;

class InterpretationController extends Controller
{
    // Show Create Form
    public function create(Request $request)
    {
        $challenge_id = $request->query('challenge_id');
        $challenge = Challenge::findOrFail($challenge_id);

        return view('interpretations.create', compact('challenge'));
    }

    // Handle Submission
    public function store(Request $request)
    {
        $request->validate([
            'challenge_id' => 'required|integer',
            'artFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('artFile');
        $filename = uniqid('interp_', true) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/uploads'), $filename);

        $user_id = Auth::id();
        $challenge_id = $request->challenge_id;

        Interpretation::create([
            'challenge_id' => $challenge_id,
            'user_id' => $user_id,
            'description' => $request->description,
            'art_filename' => $filename,
            'created_at' => now(),
        ]);

        // Notification for Author
        $challenge = Challenge::find($challenge_id);
        if ($challenge && $challenge->user_id != $user_id) {
            Notification::create([
                'recipient_user_id' => $challenge->user_id,
                'sender_user_id' => $user_id,
                'type' => 'interpretation',
                'target_id' => $challenge_id,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('challenges.show', $challenge_id);
    }

    // Show All (all_interpretations.php)
    public function index(Request $request)
    {
        $challenge_id = $request->query('challenge_id');
        $challenge = Challenge::findOrFail($challenge_id);
        $user_id = Auth::id();

        $interpretations = DB::table('interpretations as i')
            ->join('users as u', 'i.user_id', '=', 'u.user_id')
            ->where('i.challenge_id', $challenge_id)
            ->select(
                'i.*',
                'u.user_id as author_id',
                'u.user_userName',
                'u.user_profile_pic',
                DB::raw('(SELECT COUNT(*) FROM interpretation_likes WHERE interpretation_id = i.interpretation_id) as like_count'),
                DB::raw('(SELECT COUNT(*) FROM interpretation_likes WHERE interpretation_id = i.interpretation_id AND user_id = ' . ($user_id ?? 0) . ') as user_has_liked')
            )
            ->orderByDesc('i.created_at')
            ->get();

        return view('interpretations.index', compact('challenge', 'interpretations'));
    }
}
