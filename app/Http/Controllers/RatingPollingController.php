<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingPollingController extends Controller
{
    public function check(Request $request)
    {
        $profile_user_id = $request->query('user_id', Auth::id());
        $response = ['update' => false];
        $session_key = 'updated_rating_for_' . $profile_user_id;

        if ($request->session()->has($session_key)) {
            $response['update'] = true;
            $response['data'] = $request->session()->get($session_key);

            // Clear session after sending
            $request->session()->forget($session_key);
        }

        return response()->json($response);
    }
}
