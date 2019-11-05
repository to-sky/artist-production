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

if (! function_exists('cyr2lat')) {
    /**
     * Convert cyrillic to latin
     *
     * @param $str
     * @return mixed
     */
    function cyr2lat($str) {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];

        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sh','a','i','','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sh','A','I','','E','Yu','Ya'
        ];

        return str_replace($cyr, $lat, $str);
    }
}
