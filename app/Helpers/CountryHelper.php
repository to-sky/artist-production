<?php

namespace App\Helpers;

use App\Models\Country;

class CountryHelper
{
    const CODE_GERMANY = 'DE';

    /**
     * Returns list of available countries
     *
     * @param bool $prependWorld - if set adds "All world" options into countries list
     *
     * @return array
     */
    public static function getList($prependWorld = false)
    {
        $data = Country
            ::orderByRaw("code = '". self::CODE_GERMANY . "' desc")
            ->orderBy('name')
            ->pluck('name', 'id')
        ;

        if ($prependWorld) $data->prepend('All World', Country::WORLD);

        return $data;
    }
}