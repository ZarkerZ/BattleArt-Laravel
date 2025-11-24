<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Challenge;
use App\Models\Like;
use App\Models\Rating;

class ChallengeController extends Controller
{
    // Index: Uses Eloquent, so 'account_status' is automatically fetched via 'user' relationship
    public function index(Request $request)
    {
        $category = $request->query('category');

        $query = Challenge::with('user')
        ->withCount('interpretations as interpretation_count')
        ->where('status', 'active') // ADD THIS: Filter out archived art
        ->whereHas('user', function($q) {
            $q->where('account_status', 'active');
        });
        if ($category) {
            $query->where('category', $category);
        }

        $challenges = $query->orderBy('created_at', 'desc')->get();

        return view('challenges.index', compact('challenges', 'category'));
    }

    public function create()
    {
        return view('challenges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'challengeName' => 'required|string|max:255',
            'category' => 'required|string',
            'challengeDescription' => 'required|string',
            'artFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $file = $request->file('artFile');
        $filename = uniqid('art_', true) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/uploads'), $filename);

        Challenge::create([
            'user_id' => Auth::id(),
            'challenge_name' => $request->challengeName,
            'challenge_description' => $request->challengeDescription,
            'category' => $request->category,
            'original_art_filename' => $filename,
            'created_at' => now(),
        ]);

        return redirect()->route('challenges.index')->with('success', 'Challenge created successfully!');
    }

    public function edit($id)
    {
        $challenge = Challenge::where('challenge_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('challenges.edit', compact('challenge'));
    }

    public function update(Request $request, $id)
    {
        $challenge = Challenge::where('challenge_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'challengeName' => 'required|string|max:255',
            'category' => 'required|string',
            'challengeDescription' => 'required|string',
            'artFile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $challenge->challenge_name = $request->challengeName;
        $challenge->category = $request->category;
        $challenge->challenge_description = $request->challengeDescription;

        if ($request->hasFile('artFile')) {
            $oldPath = public_path('assets/uploads/' . $challenge->original_art_filename);
            if (file_exists($oldPath)) { @unlink($oldPath); }

            $file = $request->file('artFile');
            $filename = uniqid('art_', true) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads'), $filename);

            $challenge->original_art_filename = $filename;
        }

        $challenge->save();
        return redirect()->route('challenges.show', $id);
    }

    public function show($id)
    {
        // 1. Challenge Author (Eloquent handles account_status automatically)
        $challenge = Challenge::with('user')->findOrFail($id);
        $user_id = Auth::id();

        // 2. Rating
        $current_user_rating = 0;
        if ($user_id) {
            $rating = Rating::where('rater_user_id', $user_id)
                            ->where('rated_user_id', $challenge->user_id)
                            ->first();
            if ($rating) $current_user_rating = $rating->rating_value;
        }

        // 3. Likes
        $userHasLiked = $user_id ? Like::where('user_id', $user_id)->where('challenge_id', $id)->exists() : false;
        $like_count = Like::where('challenge_id', $id)->count();

        // 4. Comments (Added 'u.account_status')
        $comments = DB::table('comments as c')
            ->join('users as u', 'c.user_id', '=', 'u.user_id')
            ->where('c.challenge_id', $id)
            ->where('c.status', 'active') // ADD THIS LINE: Hide archived comments
            ->select('c.*', 'u.user_userName', 'u.user_profile_pic', 'u.user_type', 'u.account_status')
            ->orderByDesc('c.created_at')
            ->get();

        // 5. Interpretations (Added 'u.account_status')
        $interpretations = DB::table('interpretations as i')
            ->join('users as u', 'i.user_id', '=', 'u.user_id')
            ->where('i.challenge_id', $id)
            ->select(
                'i.*',
                'u.user_userName',
                'u.user_profile_pic',
                'u.account_status', // Added status
                DB::raw('(SELECT COUNT(*) FROM interpretation_likes WHERE interpretation_id = i.interpretation_id) as like_count'),
                DB::raw('(SELECT COUNT(*) FROM interpretation_likes WHERE interpretation_id = i.interpretation_id AND user_id = ' . ($user_id ?? 0) . ') as user_has_liked')
            )
            ->orderByDesc('i.created_at')
            ->limit(6)
            ->get();

        $total_interpretations = DB::table('interpretations')->where('challenge_id', $id)->count();

        return view('challenges.show', compact(
            'challenge', 'current_user_rating', 'userHasLiked', 'like_count',
            'comments', 'interpretations', 'total_interpretations'
        ));
    }

    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);
        if ($challenge->user_id !== Auth::id()) { abort(403, 'Unauthorized action.'); }

        $filePath = public_path('assets/uploads/' . $challenge->original_art_filename);
        if (file_exists($filePath)) { @unlink($filePath); }

        $challenge->delete();
        return redirect()->route('challenges.index')->with('success', 'Challenge successfully deleted.');
    }
}
