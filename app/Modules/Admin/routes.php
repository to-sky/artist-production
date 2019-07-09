<?php

use Illuminate\Support\Facades\View;
use App\Models\Menu;

Route::group([
    'module' => 'Admin',
    'namespace' => 'App\Modules\Admin\Controllers',
    'middleware' => 'web'
], function() {

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get(config('admin.route').'/locale/{locale}', 'AdminController@setLocale')->name('admin.locale');
});

Route::group([
    'module' => 'Admin',
    'namespace' => 'App\Modules\Admin\Controllers',
    'middleware' => ['web', 'auth']
], function() {
    Route::get(config('admin.homeRoute'), config('admin.homeAction','DashboardController@index'));
//    Route::resource('users', 'UserController');
//    Route::resource('roles', 'RoleController');
    Route::get('home', 'DashboardController@index');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('addresses/manage', 'AddressController@manage')->name('addresses.manage');
    Route::delete('addresses/{id}', 'AddressController@destroy')->name('addresses.destroy');
    Route::get('dataTables/locale/{locale}', 'AdminController@dataTablesLocale')->name('dataTables.locale');

    Route::patch('shippings/{shipping}', 'ShippingController@setDefaultShipping')->name('shippings.set-default');
    Route::delete('shippings/shippingZone/{shipping_zone}', 'ShippingController@deleteShippingZone')->name('shippings.delete-shipping-zone');

    Route::prefix('events')->group(function () {
        Route::delete('{event}/deleteEventImage', 'EventController@deleteEventImage')->name('events.deleteEventImage');
        Route::delete('{event}/deleteFreePassLogo', 'EventController@deleteFreePassLogo')->name('events.deleteFreePassLogo');
        Route::get('getBuildings', 'EventController@getBuildings')->name('events.getBuildings');
        Route::get('getHalls', 'EventController@getHalls')->name('events.getHalls');
        Route::get('hallPlaces/{event}', 'EventController@hallPlaces')->name(config('admin.route').'.events.hallPlaces');
        Route::delete('prices/{price}', 'EventController@deletePrice')->name('events.deletePrice');
        Route::delete('priceGroups/{price_group}', 'EventController@deletePriceGroup')->name('events.deletePriceGroup');
    });
});

if (Schema::hasTable('menus')) {
    $menus = Menu::with('children')->where('menu_type', '!=', 0)->orderBy('position')->get();
    View::share('menus', $menus);
    if (! empty($menus)) {
        Route::group([
            'module'     => 'Admin',
            'middleware' => ['web', 'auth'],
            'prefix'     => config('admin.route'),
            'as'         => config('admin.route') . '.',
            'namespace'  => 'App\Modules\Admin\Controllers',
        ], function () use ($menus) {
            foreach ($menus as $menu) {
                switch ($menu->menu_type) {
                    case 1:
                        Route::post(strtolower($menu->plural_name) . '/massDelete', [
                            'as'   => strtolower($menu->plural_name) . '.massDelete',
                            'uses' => ucfirst(camel_case($menu->singular_name)) . 'Controller@massDelete'
                        ]);
                        Route::resource(strtolower($menu->plural_name),
                            ucfirst(camel_case($menu->singular_name)) . 'Controller', ['except' => 'show']);
                        break;
                    case 3:
                        Route::get(strtolower($menu->plural_name), [
                            'as'   => strtolower($menu->plural_name) . '.index',
                            'uses' => ucfirst(camel_case($menu->singular_name)) . 'Controller@index',
                        ]);
                        break;
                }
            }

            Route::get('clients/excel', 'ClientController@excel')->name('clients.excel');
            Route::get('settings/mail', 'SettingController@mail')->name('settings.mail');
            Route::get('settings/checkout', 'SettingController@checkout')->name('settings.checkout');
            Route::post('settings/mail', 'SettingController@mailStore')->name('settings.mailStore');
            Route::post('settings/checkout', 'SettingController@checkoutStore')->name('settings.checkoutStore');
            Route::get('users/profile', 'UserController@profile')->name('users.profile');
            Route::post('users/profile', 'UserController@updateProfile')->name('users.updateProfile');
            Route::get('users/{user}/removeAvatar', 'UserController@removeAvatar')->name('users.removeAvatar');
        });
    }

}

Route::group([
    'module'     => 'Admin',
    'namespace'  => 'App\Modules\Admin\Controllers',
    'middleware' => ['web', 'auth']
], function () {
    // Menu routing
    Route::get(config('admin.route') . '/menu', [
        'as'   => config('admin.route') . '.menu.index',
        'uses' => 'MenuController@index'
    ]);
    Route::post(config('admin.route') . '/menu', [
        'as'   => config('admin.route') . '.menu.store',
        'uses' => 'MenuController@store'
    ]);
    Route::get(config('admin.route') . '/menu/create', [
        'as'   => config('admin.route') . '.menu.create',
        'uses' => 'MenuController@create'
    ]);
    Route::post(config('admin.route') . '/menu/rearrange', [
        'as'   => config('admin.route') . '.menu.rearrange',
        'uses' => 'MenuController@rearrange'
    ]);

    Route::get(config('admin.route') . '/menu/edit/{id}', [
        'as'   => config('admin.route') . '.menu.edit',
        'uses' => 'MenuController@edit'
    ]);
    Route::post(config('admin.route') . '/menu/edit/{id}', [
        'as'   => config('admin.route') . '.menu.edit',
        'uses' => 'MenuController@update'
    ]);

    Route::get(config('admin.route') . '/menu/crud', [
        'as'   => config('admin.route') . '.menu.crud',
        'uses' => 'MenuController@createCrud'
    ]);
    Route::post(config('admin.route') . '/menu/crud', [
        'as'   => config('admin.route') . '.menu.crud.insert',
        'uses' => 'MenuController@insertCrud'
    ]);

    Route::get(config('admin.route') . '/menu/parent', [
        'as'   => config('admin.route') . '.menu.parent',
        'uses' => 'MenuController@createParent'
    ]);
    Route::post(config('admin.route') . '/menu/parent', [
        'as'   => config('admin.route') . '.menu.parent.insert',
        'uses' => 'MenuController@insertParent'
    ]);

    Route::get(config('admin.route') . '/menu/custom', [
        'as'   => config('admin.route') . '.menu.custom',
        'uses' => 'MenuController@createCustom'
    ]);
    Route::post(config('admin.route') . '/menu/custom', [
        'as'   => config('admin.route') . '.menu.custom.insert',
        'uses' => 'MenuController@insertCustom'
    ]);

    Route::get(config('admin.route') . '/actions', [
        'as'   => config('admin.route') . '.actions',
        'uses' => 'UserActionsController@index'
    ]);
    Route::get(config('admin.route') . '/actions/ajax', [
        'as'   => config('admin.route') . '.actions.ajax',
        'uses' => 'UserActionsController@table'
    ]);

    Route::prefix('tickets')->group(function () {
        Route::get('print/{ticket}', 'TicketController@print')->name('tickets.print');
        Route::get('zebraPrint', 'TicketController@zebraPrint')->name('tickets.zebraPrint');
    });

    Route::prefix('admin/orders')->group(function () {
        Route::get('tickets_table', 'OrderController@updateTicketsTable')->name('order.updateTicketsTable');
        Route::get('{order}', 'TicketController@print')->name('tickets.print');
        Route::get('widget/{event}', 'OrderController@widgetContent')->name('events.widgetContent');
        Route::get('user/addresses', 'OrderController@getUserAddresses')->name('order.getAddresses');
    });
});
