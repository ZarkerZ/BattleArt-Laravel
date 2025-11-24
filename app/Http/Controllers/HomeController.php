<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Trending Logic: Sum of Likes + Comments + Interpretations
        // This calculates a score to show the most popular art on the homepage
        $trending_challenges = DB::table('challenges as c')
            ->where('c.status', 'active')
            ->join('users as u', 'c.user_id', '=', 'u.user_id')
            ->leftJoin('likes as l', 'c.challenge_id', '=', 'l.challenge_id')
            ->leftJoin('comments as co', 'c.challenge_id', '=', 'co.challenge_id')
            ->leftJoin('interpretations as i', 'c.challenge_id', '=', 'i.challenge_id')
            ->where('u.account_status', 'active')
            ->select(
                'c.challenge_id',
                'c.user_id',
                'c.challenge_name',
                'c.original_art_filename',
                'u.user_userName',
                DB::raw('COUNT(DISTINCT l.like_id) as like_count'),
                DB::raw('COUNT(DISTINCT co.comment_id) as comment_count'),
                DB::raw('(COUNT(DISTINCT l.like_id) + COUNT(DISTINCT co.comment_id) + COUNT(DISTINCT i.interpretation_id)) as trending_score')
            )
            ->groupBy('c.challenge_id', 'c.user_id', 'c.challenge_name', 'c.original_art_filename', 'u.user_userName')
            ->orderByDesc('trending_score')
            ->limit(4)
            ->get();

        return view('home', compact('trending_challenges'));
    }
}
