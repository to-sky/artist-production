<?php

namespace App\Modules\Admin\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;

class AdminComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $route = Route::currentRouteName();
        $parts = explode('.', $route);

        if (isset($parts[1])) {
            $menuRoute = Menu::where('plural_name', $parts[1])
                ->first();

            $view->with('menuRoute', $menuRoute);
        }

        if (isset($parts[2])) {
            $view->with('action', $parts[2]);
        }

        $view->with('languages', config('admin.languages'));
        $view->with('locale', App::getLocale());
    }
}