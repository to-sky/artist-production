<?php

namespace App\Providers;

/**
 * ServiceProvider
 *
 * The service provider for the modules. After being registered
 * it will make sure that each of the modules are properly loaded
 * i.e. with their routes, views etc.
 *
 * @author kundan Roy <query@programmerlab.com>
 * @package App\Modules
 */

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ModulesServiceProvider extends ServiceProvider {

    /**
     * Will make sure that the required modules have been fully loaded
     *
     * @return void routeModule
     */
    public function boot() {
        // For each of the registered modules, include their routes and Views
        $modules = config("module.modules");
        foreach ($modules as $module) {
            $routesPath = base_path('app/Modules') . '/' . $module . '/routes.php';
            if (file_exists($routesPath)) {
                include $routesPath;
            }
            $viewPath = base_path('app/Modules') . '/' . $module . '/resources/views';
            if (is_dir($viewPath)) {
                $this->loadViewsFrom($viewPath, $module);
            }
            $languagePath = base_path('app/Modules') . '/' . $module . '/resources/lang';
            if (is_dir($languagePath)) {
                $this->loadTranslationsFrom($languagePath, $module);
            }
            // publish public assets
            $publicPath = base_path('app/Modules') . '/' . $module . '/resources/assets';
            if (is_dir($publicPath)) {
                $this->publishes([$publicPath => public_path(strtolower($module))], 'modules');
            }
        }

    }

    public function register() { }

}