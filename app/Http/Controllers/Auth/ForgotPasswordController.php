<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; // You must configure mail in .env

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('user_email', $request->email)->first();

        if ($user) {
            // Generate Token
            $token = Str::random(32); // Using Laravel Str helper is cleaner than bin2hex
            $token_hash = hash('sha256', $token);

            $user->reset_token_hash = $token_hash;
            $user->reset_token_expires_at = Carbon::now()->addMinutes(30);
            $user->save();

            // Send Email using standard Laravel Mail
            // Note: You need to create a Mailable, or use raw for quick migration
            $resetLink = url('/reset-password?token=' . $token);

            try {
                Mail::send([], [], function ($message) use ($user, $resetLink) {
                    $message->to($user->user_email)
                            ->subject('Password Reset Request')
                            ->html('Click <a href="'.$resetLink.'">here</a> to reset your password.');
                });
            } catch (\Exception $e) {
                // Log error
            }
        }

        return back()->with('message', 'If an account with that email exists, a password reset link has been sent.');
    }
}
