<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Modules\Admin\Controllers\Traits\SaveActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Modules\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class UserController extends AdminController
{

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'birth_date' => 'nullable|date', // TODO: Define validation for birth_date from the future |before:2011-01-01',
            'gender' => 'nullable|string|max:45',
            'email' => 'sometimes|nullable|required|string|email|max:255|unique:users',
            'password' => 'sometimes|nullable|string|required|min:6|confirmed',
        ]);
    }

    /**
     * Show a list of users
     * @return \Illuminate\View\View
     */
    public function index()
    {
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
    public function edit($id)
    {
        $user  = User::findOrFail($id);
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
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $user = User::findOrFail($id);
        $input['password'] = Hash::make($input['password']);
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