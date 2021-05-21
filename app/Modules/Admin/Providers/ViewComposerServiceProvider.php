<?php

namespace App\Modules\Admin\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Models\Menu;


class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('Admin::*', 'App\Modules\Admin\ViewComposers\AdminComposer');
        View::composer('payment.*', 'App\Modules\Admin\ViewComposers\AdminComposer');
        View::composer(['Admin::*.edit*', 'Admin::*.create*'], 'App\Modules\Admin\ViewComposers\FormComposer');
        View::composer(['Admin::setting.*'], 'App\Modules\Admin\ViewComposers\FormComposer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
