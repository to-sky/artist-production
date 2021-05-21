<?php

namespace App\Modules\Admin\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Closure;
use App\Modules\Admin\Controllers\Traits\SaveButtons;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;

class ShareViewVariables extends Middleware
{

    use SaveButtons;


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = Route::currentRouteName();

        $parts = explode('.', $route);

        if (isset($parts[1])) {
            $this->menu = Menu::where('plural_name', $parts[1])
                ->first();

            View::share('menu', $this->menu);
        }

        if (isset($parts[2])) {

            View::share('action', $parts[2]);

            if ($parts[2] == 'edit' || $parts[2] == 'create') {
                View::share('saveButtons', $this->getSaveButtons());
            }
        }

        return $next($request);
    }
}