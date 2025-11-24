<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EditProfileController extends Controller
{
    // Show the visual edit form
    public function edit()
    {
        $user = Auth::user();

        // Determine redirect link based on role
        $profileLink = ($user->user_type === 'admin')
            ? route('admin.profile')
            : route('profile.show', $user->user_id);

        return view('profile.edit', compact('user', 'profileLink'));
    }

    // Handle the form submission (Cropping & Updates)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullName' => 'required|string|max:255',
            'userBio' => 'nullable|string|max:500',
        ]);

        // 1. Handle CROPPED Profile Picture (Base64)
        if ($request->filled('croppedImage')) {
            $data = $request->input('croppedImage');
            // Clean up the base64 string (remove "data:image/png;base64," header)
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                $data = base64_decode($data);

                if ($data) {
                    $filename = uniqid('profile_', true) . '.' . $type;
                    $path = public_path('assets/uploads/' . $filename);

                    // Ensure directory exists
                    if (!File::exists(public_path('assets/uploads'))) {
                        File::makeDirectory(public_path('assets/uploads'), 0755, true);
                    }

                    file_put_contents($path, $data);

                    // Delete old image if it's not the default
                    if ($user->user_profile_pic && $user->user_profile_pic !== 'default_avatar.png') {
                        $oldPath = public_path('assets/uploads/' . $user->user_profile_pic);
                        if (File::exists($oldPath)) File::delete($oldPath);
                    }

                    $user->user_profile_pic = $filename;
                }
            }
        }

        // 2. Handle CROPPED Banner Picture (Base64)
        if ($request->filled('croppedBanner')) {
            $data = $request->input('croppedBanner');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                $data = base64_decode($data);

                if ($data) {
                    $filename = uniqid('banner_', true) . '.' . $type;
                    $path = public_path('assets/uploads/' . $filename);

                    if (!File::exists(public_path('assets/uploads'))) {
                        File::makeDirectory(public_path('assets/uploads'), 0755, true);
                    }

                    file_put_contents($path, $data);
                    $user->user_banner_pic = $filename;
                }
            }
        }

        // 3. Update Basic Info
        $user->user_userName = $request->fullName; // Map "fullName" input to "user_userName"
        $user->user_bio = $request->userBio;

        // 4. Update Visibility Toggles
        $user->show_art = $request->has('toggleArt') ? 1 : 0;
        $user->show_history = $request->has('toggleHistory') ? 1 : 0;
        $user->show_comments = $request->has('toggleComments') ? 1 : 0;

        $user->save();

        // Redirect logic
        $redirectRoute = ($user->user_type === 'admin') ? 'admin.profile' : 'profile.show';
        $redirectParams = ($user->user_type === 'admin') ? [] : ['id' => $user->user_id];

        return redirect()->route($redirectRoute, $redirectParams)
                         ->with('success', 'Profile updated successfully!');
    }
}
