<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\InterpretationLike;
use App\Models\Rating;
use App\Models\Challenge;
use App\Models\Interpretation;
use App\Models\Notification;

class InteractionController extends Controller
{
    // Handle Challenge Like
    public function toggleChallengeLike(Request $request)
    {
        $user_id = Auth::id();
        $challenge_id = $request->challenge_id;

        $like = Like::where('user_id', $user_id)->where('challenge_id', $challenge_id)->first();
        $userHasLiked = false;

        if ($like) {
            $like->delete();
        } else {
            Like::create(['user_id' => $user_id, 'challenge_id' => $challenge_id, 'created_at' => now()]);
            $userHasLiked = true;

            // Notify Owner
            $challenge = Challenge::find($challenge_id);
            if ($challenge && $challenge->user_id != $user_id) {
                Notification::create([
                    'recipient_user_id' => $challenge->user_id,
                    'sender_user_id' => $user_id,
                    'type' => 'like',
                    'target_id' => $challenge_id,
                    'created_at' => now()
                ]);
            }
        }

        $count = Like::where('challenge_id', $challenge_id)->count();
        return response()->json(['success' => true, 'likeCount' => $count, 'userHasLiked' => $userHasLiked]);
    }

    // Handle Interpretation Like
    public function toggleInterpretationLike(Request $request)
    {
        $user_id = Auth::id();
        $interp_id = $request->interpretation_id;

        $like = InterpretationLike::where('user_id', $user_id)->where('interpretation_id', $interp_id)->first();
        $userHasLiked = false;

        if ($like) {
            $like->delete();
        } else {
            InterpretationLike::create(['user_id' => $user_id, 'interpretation_id' => $interp_id, 'created_at' => now()]);
            $userHasLiked = true;

            // Notify Owner
            $interpretation = Interpretation::find($interp_id);
            if ($interpretation && $interpretation->user_id != $user_id) {
                Notification::create([
                    'recipient_user_id' => $interpretation->user_id,
                    'sender_user_id' => $user_id,
                    'type' => 'interpretation_like',
                    'target_id' => $interp_id,
                    'target_parent_id' => $interpretation->challenge_id,
                    'created_at' => now()
                ]);
            }
        }

        $count = InterpretationLike::where('interpretation_id', $interp_id)->count();
        return response()->json(['success' => true, 'likeCount' => $count, 'userHasLiked' => $userHasLiked]);
    }

    // Handle Rating
    public function rateUser(Request $request)
    {
        $rater_id = Auth::id();
        $rated_id = $request->rated_user_id;
        $value = $request->rating_value;

        if ($rater_id == $rated_id) {
            return response()->json(['success' => false, 'error' => 'You cannot rate yourself.']);
        }

        Rating::updateOrCreate(
            ['rated_user_id' => $rated_id, 'rater_user_id' => $rater_id],
            ['rating_value' => $value, 'created_at' => now()]
        );

        $avg = Rating::where('rated_user_id', $rated_id)->avg('rating_value');
        $count = Rating::where('rated_user_id', $rated_id)->count();

        return response()->json([
            'success' => true, 
            'newAverage' => round($avg, 1), 
            'newCount' => $count
        ]);
    }
}