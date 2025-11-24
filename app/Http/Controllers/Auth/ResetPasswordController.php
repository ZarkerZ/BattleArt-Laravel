<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            abort(403, 'No token provided.');
        }
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $token_hash = hash("sha256", $request->token);

        // Find user with valid token
        $user = User::where('reset_token_hash', $token_hash)
                    ->where('reset_token_expires_at', '>', Carbon::now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Token not found or has expired.']);
        }

        // Update password
        $user->user_password = Hash::make($request->password);
        $user->reset_token_hash = null;
        $user->reset_token_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Password has been reset successfully.');
    }
}