<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegisterController extends Controller
{
    // Define the secret admin code from your original code
    const ADMIN_SECRET_CODE = 'JUMBOHOTDOG';

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function register(Request $request)
    {
        // 1. Validate the incoming data
        $validator = Validator::make($request->all(), [
            'firstName' => ['required', 'string', 'max:255'],
            'middleName' => ['nullable', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'userName' => ['required', 'string', 'max:255', 'unique:users,user_userName'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,user_email'],
            'password' => ['required', 'string', 'min:6'],
            'confirmPassword' => ['required', 'same:password'],
            'dob' => ['required', 'date', 'before:today'],
            'user_type' => ['required', 'in:user,admin'],
            'admin_code' => ['nullable', 'string'], // We validate logic below
        ], [
            // Custom error messages to match your original wording
            'userName.unique' => 'This username is already taken.',
            'email.unique' => 'This email is already taken.',
            'confirmPassword.same' => 'Passwords did not match.',
        ]);

        // 2. Custom Logic Validation (Age & Admin Code)
        $validator->after(function ($validator) use ($request) {
            // Age Check (Must be 13+)
            if ($request->dob) {
                $age = Carbon::parse($request->dob)->age;
                if ($age < 13) {
                    $validator->errors()->add('dob', 'You must be at least 13 years old to register.');
                }
            }

            // Admin Code Check
            if ($request->user_type === 'admin') {
                if (empty($request->admin_code)) {
                    $validator->errors()->add('admin_code', 'Please enter the admin code.');
                } elseif ($request->admin_code !== self::ADMIN_SECRET_CODE) {
                    $validator->errors()->add('admin_code', 'Invalid admin code.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // Keep the user's input in the form
        }

        // 3. Create the User
        User::create([
            'user_firstName' => $request->firstName,
            'user_middleName' => $request->middleName,
            'user_lastName' => $request->lastName,
            'user_email' => $request->email,
            'user_userName' => $request->userName,
            'user_dob' => $request->dob,
            'user_type' => $request->user_type,
            'user_password' => Hash::make($request->password),
            // Default values
            'user_profile_pic' => 'default_avatar.png',
            'allow_notifications' => 1,
            'show_art' => 1,
            'account_status' => 'active',
        ]);

        // 4. Redirect to login (or dashboard)
        // In Laravel, we typically redirect to a named route.
        // We will create the login route in the next step.
        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }
}