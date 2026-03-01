<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit()
    {
        $userId = Auth::user()->id;
        $user = Admin::where('id', $userId)->first();

        return view('admin.profile.edit', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->passes()) {
            $user = Admin::find(Auth::user()->id);
            $user->name = $request->name;
            $user->save();

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } else {
            return Redirect::back()->withErrors($validator);
        }
    }

    public function changePassword()
    {
        return view('admin.profile.change-password');
    }

    public function changePasswordProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->passes()) {
            $user = Admin::select('id', 'password')->where('id', Auth::user()->id)->first();

            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Your old password is incorrect, please try again.');
            }

            Admin::where('id', Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);

            return redirect()->back()->with('success', 'You have successfully change your password.');
        } else {
            return Redirect::back()->withErrors($validator);
        }
    }
}
