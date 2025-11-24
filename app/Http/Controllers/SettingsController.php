<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function index()
    {
        return view('profile.settings', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullName' => 'required|string|max:255',
            'userBio' => 'nullable|string|max:500',
        ]);

        // 1. Handle Base64 Profile Pic Upload (from Cropper.js)
        if ($request->filled('croppedImage')) {
            $data = $request->input('croppedImage');

            // Extract base64 data (e.g., "data:image/png;base64,AAAFB...")
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc.
                $data = base64_decode($data);

                if ($data) {
                    $filename = uniqid('profile_', true) . '.' . $type;
                    // Save to public/assets/uploads/
                    file_put_contents(public_path('assets/uploads/' . $filename), $data);
                    $user->user_profile_pic = $filename;
                }
            }
        }

        // 2. Handle Base64 Banner Upload
        if ($request->filled('croppedBanner')) {
            $data = $request->input('croppedBanner');

            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                $data = base64_decode($data);

                if ($data) {
                    $filename = uniqid('banner_', true) . '.' . $type;
                    file_put_contents(public_path('assets/uploads/' . $filename), $data);
                    $user->user_banner_pic = $filename;
                }
            }
        }

        // 3. Update Text Fields
        $user->user_userName = $request->fullName; // Mapping fullName to userName as per your form
        $user->user_bio = $request->userBio;

        // 4. Update Toggles
        $user->show_art = $request->has('toggleArt') ? 1 : 0;
        $user->show_history = $request->has('toggleHistory') ? 1 : 0;
        $user->show_comments = $request->has('toggleComments') ? 1 : 0;

        $user->save();

        return redirect()->back()->with('message', ['text' => 'Profile updated successfully!', 'type' => 'success']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:6|different:currentPassword',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->currentPassword, $user->user_password)) {
            return back()->with('message', ['text' => 'Incorrect current password.', 'type' => 'danger']);
        }

        $user->update(['user_password' => Hash::make($request->newPassword)]);

        return back()->with('message', ['text' => 'Password updated successfully!', 'type' => 'success']);
    }

    public function toggleNotifications(Request $request)
    {
        // For the AJAX call
        $user = Auth::user();
        $user->allow_notifications = $request->notifications ? 1 : 0;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return redirect()->route('login')->with('deleted', true);
    }
}
