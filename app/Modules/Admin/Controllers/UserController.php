<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Modules\Admin\Services\RedirectService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Modules\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Prologue\Alerts\Facades\Alert;

class UserController extends AdminController
{

    public function __construct(RedirectService $redirectService)
    {
        $this->authorizeResource(User::class, 'user');

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
            'last_name' => 'string|max:255',
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

        $users = User::all();

        return view('Admin::user.index', compact('users'));
    }

    /**
     * Show a page of user creation
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::pluck('display_name', 'id');

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

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
    }

    /**
     * Show a user edit page
     *
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('display_name', 'id');
        
        return view('Admin::user.edit', compact('user', 'roles'));
    }

    /**
     * Update our user information
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, Request $request)
    {
        $input = $request->all();
        $this->validator($input, $user)->validate();

        if (!empty($input['password'])) {
             $user->password = Hash::make($input['password']);
        }
        $role = Role::findOrFail($input['role_id']);
        $user->update($input);
        // Detaches old role and attaches the new one
        if (!$user->hasRole($role->name)) {
            $user->detachRoles($user->roles);
            $user->attachRole($role);
        }

        Alert::success(trans('Admin::admin.users-controller-successfully_updated'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
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
}