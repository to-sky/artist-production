<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Modules\Admin\Services\RedirectService;
use App\Services\UploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Modules\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Prologue\Alerts\Facades\Alert;
use App\Helpers\FileHelper;

class UserController extends AdminController
{

    /**
     * @var UploadService
     */
    protected $uploadService;

    public function __construct(RedirectService $redirectService, UploadService $uploadService)
    {
        $this->authorizeResource(User::class, 'user');

        $this->uploadService = $uploadService;

        parent::__construct($redirectService);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $user = null)
    {
        return Validator::make($data, [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore((isset($user->id) ? $user->id : null)),
            ],
            'password' => 'nullable|confirmed|string||min:6|',
        ]);
    }

    /**
     * Show a list of users
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('index', User::class);

        $users = User::whereDoesntHave('roles', function ($q) {
            $q->whereName(Role::CLIENT);
        })->get();

        return view('Admin::user.index', compact('users'));
    }

    /**
     * Show a page of user creation
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::where('name', '!=', Role::CLIENT)->pluck('display_name', 'id');

        return view('Admin::user.create', compact('roles'));
    }

    /**
     * Insert new user into the system
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $input['password'] = Hash::make($input['password']);
        $role = Role::findOrFail($input['role_id']);
        $user = User::create($input);
        // Attaches role to user
        $user->attachRole($role);

        // Manages avatar upload
        $this->uploadService->uploadAvatar($request, $user);

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
    }

    /**
     * Show a user edit page
     *
     * @param $user
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', Role::CLIENT)->pluck('display_name', 'id');

        return view('Admin::user.edit', compact('user', 'roles'));
    }

    /**
     * Update our user information
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, Request $request)
    {
        $this->updateUser($user, $request);

        Alert::success(trans('Admin::admin.users-controller-successfully_updated'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
    }

    /**
     * Validate request and update user
     *
     * @param User $user
     * @param Request $request
     * @return User
     */
    public function updateUser(User $user, Request $request)
    {
        $input = $request->all();
        $this->validator($input, $user)->validate();

        // Manages avatar upload
        $this->uploadService->uploadAvatar($request, $user);

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }
        $role = Role::findOrFail($input['role_id']);
        $user->update($input);
        // Detaches old role and attaches the new one
        if (!$user->hasRole($role->name)) {
            $user->detachRoles($user->roles);
            $user->attachRole($role);
        }

        return $user;
    }

    /**
     * Destroy specific user
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        User::destroy($id);

        return response()->json(null, 204);

//        return redirect()->route('users.index')->withMessage(trans('Admin::admin.users-controller-successfully_deleted'));
    }

    /**
     * Show user profile view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();

        $roles = Role::where('name', '!=', Role::CLIENT)->pluck('display_name', 'id');

        return view('Admin::user.profile', compact('user', 'roles'));
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $this->updateUser($user, $request);

        Alert::success(__('Profile was successfully updated!'))->flash();

        return redirect()->back();
    }

    /**
     * Remove user avatar
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAvatar(User $user)
    {
        $avatar = $user->avatar;
        if (!empty($avatar)) {
            FileHelper::delete($user->avatar);
            FileHelper::deleteThumb($user->avatar);
            $avatar->name = null;
            $avatar->mime = null;
            $avatar->original_name = null;
            $avatar->thumbnail = null;
            $avatar->save();
        } else {
            return response()->json(['message' => __('Avatar not found')], 404);
        }

        return response()->json(null, 204);
    }
}
