<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // 1. Validate Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Prepare credentials for Auth::attempt
        // We map the input 'email' to the database column 'user_email'
        // We keep 'password' as is, because Auth expects the plain text key to be 'password'
        $authCredentials = [
            'user_email' => $credentials['email'],
            'password' => $credentials['password']
        ];

        // 3. Handle "Remember Me"
        $remember = $request->has('rememberMe');

        // 4. Attempt Login
        if (Auth::attempt($authCredentials, $remember)) {
            $user = Auth::user();

            // 5. CHECK BAN STATUS
            if ($user->account_status === 'banned') {
                Auth::logout(); // Log them out immediately
                return back()
                    ->withInput($request->only('email', 'rememberMe'))
                    ->with('is_banned', true) // Flag to trigger modal
                    ->withErrors(['email' => 'Your account has been banned.']);
            }

            // 6. Login Successful: Update Logic
            $request->session()->regenerate();

            // Update last_seen
            $user->last_seen = now();
            $user->save(); // Eloquent handles the UPDATE query

            // 7. Redirect based on User Type
            if ($user->user_type === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            // Standard user redirect
            return redirect()->intended('/profile');
        }

        // 8. Login Failed
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email', 'rememberMe'));
    }
}