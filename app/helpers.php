<?php

/**
 * Convert integer to string
 *
 * @param $number
 * @return array|string|null
 */
function numberToString($number){
    return $number == 1 ? __('Admin::strings.yes') : __('Admin::strings.no');
}
