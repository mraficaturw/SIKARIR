<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use App\Models\Internjob;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        // Eager load relationships dengan error handling
        $favorites = $user->favorites ?? collect();
        $appliedJobs = $user->appliedJobs ?? collect();

        return view('profile.profile', compact('user', 'favorites', 'appliedJobs'));
    }

    public function editForm()
    {
        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        return view('profile.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:5120', // Max 5MB
        ]);

        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        $user->name = $request->name;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('supabase-avatar')->delete($user->avatar);
            }

            // Convert and upload new avatar as WebP
            $path = ImageService::convertAndUpload(
                $request->file('avatar'),
                'supabase-avatar',
                'avatars',
                80
            );

            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    public function changePasswordForm()
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('status', 'Password changed successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('status', 'Password updated successfully.');
    }
}
