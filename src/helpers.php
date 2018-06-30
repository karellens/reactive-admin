<?php
/**
 * Created by PhpStorm.
 * User: karellen
 * Date: 6/30/18
 * Time: 5:59 PM
 */

if (! function_exists('str_wrap')) {
    /**
     * Wrap the string with given surroundings
     *
     * @param  string  $value
     * @return string
     */
    function str_wrap($value, $surroundings)
    {
        return strlen($value) ? implode($value, $surroundings) : '';
    }
}