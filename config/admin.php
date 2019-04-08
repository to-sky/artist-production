<?php

return [

    /**
     * Datepicker configuration:
     */
    'date_format'        => 'Y-m-d',
    'date_format_jquery' => 'yy-mm-dd',
    'time_format'        => 'H:i:s',
    'time_format_hm'     => 'H:i',
    'time_format_jquery' => 'HH:mm:ss',

    /**
     * Admin settings
     */
    // Default route
    'route'              => 'admin',
    // Default home route
    'homeRoute'          => 'dashboard',
    //Default home action
    // 'homeAction' => '\App\Http\Controllers\MyOwnController@index',
    // Default role to access users and CRUD
    'defaultRole'        => 1,
    'inlineErrorsEnabled' => true,
    'focusFirstField' => true,
    'skin' => 'skin-black-light',
    'registrationEnabled' => true,
    'defaultRedirect' => 'redirect_back',
    'languages' => [
        'ru' => [
            'title' => 'Русский',
            'flag' => 'ru'
        ],
        'en' => [
            'title' => 'English',
            'flag' => 'gb'
        ]
    ]

];
