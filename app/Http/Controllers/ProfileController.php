<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Interpretation;

class ProfileController extends Controller
{
    public function show($id = null)
    {
        // If no ID provided, show logged in user's profile, or redirect to login
        if (!$id) {
            if (!Auth::check()) return redirect()->route('login');
            $id = Auth::id();
        }

        $user = User::findOrFail($id);
        $viewerId = Auth::id(); // Can be null if guest

        // 1. Stats
        $challenges_count = Challenge::where('user_id', $id)->count();
        $interpretations_count = Interpretation::where('user_id', $id)->count();
        $total_art = $challenges_count + $interpretations_count;

        // 2. Your Art Tab
        $user_challenges = Challenge::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. History Tab (Complex UNION Query)
        $historySql = "
            (SELECT 'created_challenge' as event_type, challenge_name as event_title, NULL as event_content, challenge_id, created_at as event_date FROM challenges WHERE user_id = ?)
            UNION
            (SELECT 'posted_comment' as event_type, ch.challenge_name, co.comment_text, co.challenge_id, co.created_at FROM comments co JOIN challenges ch ON co.challenge_id = ch.challenge_id WHERE co.user_id = ?)
            UNION
            (SELECT 'created_interpretation' as event_type, ch.challenge_name, i.description, i.challenge_id, i.created_at FROM interpretations i JOIN challenges ch ON i.challenge_id = ch.challenge_id WHERE i.user_id = ?)
            UNION
            (SELECT 'liked_challenge' as event_type, ch.challenge_name, NULL, l.challenge_id, l.created_at FROM likes l JOIN challenges ch ON l.challenge_id = ch.challenge_id WHERE l.user_id = ?)
            UNION
            (SELECT 'liked_interpretation' as event_type, u.user_userName, ch.challenge_name, ch.challenge_id, il.created_at FROM interpretation_likes il JOIN interpretations i ON il.interpretation_id = i.interpretation_id JOIN users u ON i.user_id = u.user_id JOIN challenges ch ON i.challenge_id = ch.challenge_id WHERE il.user_id = ?)
            ORDER BY event_date DESC LIMIT 10
        ";
        $user_history = DB::select($historySql, [$id, $id, $id, $id, $id]);

        // 4. Comments Tab
        $comments_on_art = DB::table('comments as co')
            ->join('users as u', 'co.user_id', '=', 'u.user_id')
            ->join('challenges as ch', 'co.challenge_id', '=', 'ch.challenge_id')
            ->where('ch.user_id', $id)
            ->where('co.user_id', '!=', $id)
            ->select('co.*', 'u.user_userName', 'u.user_profile_pic', 'ch.challenge_name')
            ->orderBy('co.created_at', 'desc')
            ->limit(10)
            ->get();

        // 5. Ratings
        $ratingInfo = DB::table('ratings')
            ->where('rated_user_id', $id)
            ->selectRaw('AVG(rating_value) as avg_rating, COUNT(*) as rating_count')
            ->first();

        $avg_rating = $ratingInfo->rating_count > 0 ? round($ratingInfo->avg_rating, 1) : 0;
        $rating_count = $ratingInfo->rating_count;

        // View logic to determine if it's "My Profile" or "Public Profile"
        $isOwnProfile = ($id == $viewerId);

        return view('profile.show', compact(
            'user', 'challenges_count', 'interpretations_count', 'total_art', 
            'user_challenges', 'user_history', 'comments_on_art', 'avg_rating', 
            'rating_count', 'isOwnProfile'
        ));
    }
}