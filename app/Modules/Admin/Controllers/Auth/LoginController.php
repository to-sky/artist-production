<?php

namespace App\Modules\Admin\Controllers\Auth;

use App\Modules\Admin\Services\RedirectService;
use Illuminate\Foundation\Auth\AuthenticatesUsers AS AuthenticatesUsers;
use App\Modules\Admin\Controllers\AdminController AS AdminController;

class LoginController extends AdminController
{

    /*
        |--------------------------------------------------------------------------
        | Login Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles authenticating users for the application and
        | redirecting them to your home screen. The controller uses a trait
        | to conveniently provide its functionality to your applications.
        |
        */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $redirectService;

    /**
     * Create a new controller instance.
     *
     * @param RedirectService $redirectService
     */
    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;

        $this->middleware('guest')->except('logout');
        $this->redirectTo = config('admin.homeRoute');
    }

    protected function redirectTo()
    {
        $redirectRoute = $this->redirectService->getHomeRedirectRoute();

        return is_null($redirectRoute) ? $this->redirectTo : route($redirectRoute);
    }
}