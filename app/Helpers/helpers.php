<?php

if (! function_exists('log_var')) {

    /**
     * Sends variable to log file
     *
     * @param $variable
     * @param string $name
     */
    function log_var($variable, $name = 'var') {
        error_log("\r\n $name \r\n" . var_export($variable, true), 3, base_path('error.log'));
    }
}

if (! function_exists('numberToString')) {
    /**
     * Convert integer to string
     *
     * @param $number
     * @return array|string|null
     */
    function numberToString($number)
    {
        return $number ? __('Admin::strings.yes') : __('Admin::strings.no');
    }
}