<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use App\Models\Internjob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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