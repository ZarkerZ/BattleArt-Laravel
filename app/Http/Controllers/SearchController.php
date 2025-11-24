<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $challenges = [];
        $users = [];

        if (!empty($query)) {
            // 1. Search Challenges (Exclude non-active users)
            $challenges = DB::table('challenges as c')
                ->where('c.status', 'active')
                ->join('users as u', 'c.user_id', '=', 'u.user_id')
                ->where('u.account_status', 'active') // ADD THIS LINE
                ->where(function($q) use ($query) {
                    $q->where('c.challenge_name', 'like', "%{$query}%")
                      ->orWhere('u.user_userName', 'like', "%{$query}%");
                })
                ->select('c.*', 'u.user_userName')
                ->orderByDesc('c.created_at')
                ->get();

            // 2. Search Users (Exclude non-active users)
            $users = DB::table('users')
                ->where('account_status', 'active') // ADD THIS LINE
                ->where('user_userName', 'like', "%{$query}%")
                ->select('user_id', 'user_userName', 'user_profile_pic')
                ->get();
        }

        return view('search.results', compact('query', 'challenges', 'users'));
    }
}
