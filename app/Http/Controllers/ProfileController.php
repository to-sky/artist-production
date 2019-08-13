<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Http\Request;

use Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$user->hasRole(Role::CLIENT)) return redirect()->route('admin.dashboard');

        return view('profile.show', compact('user', 'profile'));
    }

    public function update(UserService $userService, ProfileRequest $request)
    {
        $user = Auth::user();

        $userService->updateFromProfileRequest($request, $user);

        return redirect()->back();
    }


}
