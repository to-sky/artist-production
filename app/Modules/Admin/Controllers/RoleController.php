<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Modules\Controller;
use Illuminate\Routing\Route;
use Prologue\Alerts\Facades\Alert;

class RoleController extends AdminController
{

    /**
     * Show a list of roles
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::get();

        return view('Admin::role.index', compact('roles'));
    }

    /**
     * Show a page of user creation
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Admin::role.create');
    }

    /**
     * Insert new role into the system
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Role::create($request->all());

        Alert::success(trans('Admin::admin.roles-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
    }

    /**
     * Show a role edit page
     *
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('Admin::role.edit', compact('role'));
    }

    /**
     * Update our role information
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        Role::findOrFail($id)->update($request->all());

        Alert::success(trans('Admin::admin.roles-controller-successfully_updated'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
    }

    /**
     * Destroy specific role
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}

