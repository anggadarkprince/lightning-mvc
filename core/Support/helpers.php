<?php

if (!function_exists('format_date')) {
    function format_date($dateString, $format = 'Y-m-d')
    {
        return (new DateTime($dateString))->format($format);
    }
}

if (!function_exists('if_empty')) {
    function if_empty($value, $default = '')
    {
        if (empty($value)) {
            return $default;
        }
        return $value;
    }
}

if (!function_exists('url')) {
    function url($path)
    {
        $url = \Core\Base\Support::config('app.url');

        return rtrim($url, '/') . '/' . rtrim($path, '/');
    }
}