<?php

namespace App\Modules\Admin\Services;


use App\Models\Role;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RedirectService
{

    static protected $_roles;

    /**
     * Homepage redirect
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function homeRedirect()
    {
        if (!auth()->check()) return redirect()->route(config('auth.redirectRoutes.login'));

        $redirectRoute = $this->getHomeRedirectRoute();

        if (is_null($redirectRoute)) {
            throw new AccessDeniedHttpException('Access denied');
        } else {
            return redirect()->route($redirectRoute);
        }
    }

    /**
     * Get homepage redirect route
     *
     * @return \Illuminate\Config\Repository|mixed|null
     */
    public function getHomeRedirectRoute()
    {
        if (!auth()->check()) self::$_roles = ['guest'];

        if (empty(self::$_roles)) {
            /** @var User $user */
            $user = auth()->user();
            self::$_roles = $user->roles()->pluck('name')->toArray();
        }

        if (
            in_array(Role::ADMIN, self::$_roles) ||
            in_array(Role::BOOKKEEPER, self::$_roles) ||
            in_array(Role::PARTNER, self::$_roles)
        ) return config('auth.redirectRoutes.admin');

        if (in_array(Role::CLIENT, self::$_roles))
            return config('auth.redirectRoutes.client');

        return null;
    }

    /**
     * Gets redirect option from the session
     *
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public function getRedirect()
    {
        return session('save_redirect', config('admin.defaultRedirect', 'redirect_back'));
    }

    /**
     * Sets the selected option to the session
     *
     * @param $request
     * @param null $forceRedirect
     */
    public function setRedirect($request, $forceRedirect = null)
    {
        if ($forceRedirect) {
            $redirect = $forceRedirect;
        } else {
            $redirect = $request->input('save_redirect', config('admin.defaultRedirect', 'redirect_back'));
        }

        session(['save_redirect' => $redirect]);
    }

    /**
     * Redirects to the selected option
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($request)
    {
        $redirect = $request->input('save_redirect', config('admin.defaultRedirect', 'redirect_back'));
        $route = $request->route()->getName();
        $parts = explode('.', $route);
        $action = array_pop($parts);

        switch ($redirect) {
            case 'redirect_back':
            default:
                return redirect()->route(implode('.', $parts) . '.index');
                break;
            case 'redirect_new':
                return redirect()->route(implode('.', $parts) . '.create');
                break;
            case 'redirect_stay':
                if ($request->input('id', null)) {
                    return redirect()->route(implode('.', $parts) . '.edit', ['id' => $request->input('id')]);
                } else {
                    return redirect()->route(implode('.', $parts) . '.create');
                }
                break;
        }
    }

    /**
     * Returns the save buttons
     *
     * @return array
     */
    public function getSaveButtons()
    {
        $redirect = $this->getRedirect();
        $active = [
            'redirect' => $redirect,
            'text' => $this->getButtonName($redirect),
        ];

        $list = [];
        switch ($redirect) {
            case 'redirect_back':
            default:
                $list['redirect_stay'] = $this->getButtonName('redirect_stay');
                $list['redirect_new'] = $this->getButtonName('redirect_new');
                break;
            case 'redirect_stay':
                $list['redirect_back'] = $this->getButtonName('redirect_back');
                $list['redirect_new'] = $this->getButtonName('redirect_new');
                break;
            case 'redirect_new':
                $list['redirect_back'] = $this->getButtonName('redirect_back');
                $list['redirect_stay'] = $this->getButtonName('redirect_stay');
                break;
        }

        return [
            'active' => $active,
            'list' => $list,
        ];
    }

    /**
     * Gets the translated text for the button
     *
     * @param string $redirect
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    private function getButtonName($redirect)
    {
        switch ($redirect) {
            case 'redirect_back':
            default:
                return trans('Admin::templates.save-and-back');
                break;
            case 'redirect_new':
                return trans('Admin::templates.save-and-new');
                break;
            case 'redirect_stay':
                return trans('Admin::templates.save-and-stay');
                break;
        }
    }
}
