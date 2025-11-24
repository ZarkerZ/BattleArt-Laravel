<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Challenge;
use App\Models\Notification;
use Waad\ProfanityFilter\Facades\ProfanityFilter;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'challenge_id' => 'required|integer|exists:challenges,challenge_id',
            'comment_text' => [
                'required',
                'string',
                'max:1000',
                // Custom validation closure using the package
                function ($attribute, $value, $fail) {
                    if (ProfanityFilter::hasProfanity($value)) {
                        $fail('Your comment contains inappropriate language.');
                    }
                },
            ],
        ]);

        $user = Auth::user();

        Comment::create([
            'user_id' => $user->user_id,
            'challenge_id' => $request->challenge_id,
            'comment_text' => $request->comment_text,
            'created_at' => now(),
        ]);

        // 4. Create Notification for the Challenge Owner
        $challenge = Challenge::find($request->challenge_id);

        // Only notify if the commenter is NOT the owner of the art
        if ($challenge && $challenge->user_id != $user->user_id) {
            Notification::create([
                'recipient_user_id' => $challenge->user_id,
                'sender_user_id' => $user->user_id,
                'type' => 'comment',
                'target_id' => $request->challenge_id,
                'is_read' => 0,
                'created_at' => now(),
            ]);
        }

        // 5. Redirect back to the page
        return redirect()->back()->with('success', 'Comment posted successfully!');
    }
}
