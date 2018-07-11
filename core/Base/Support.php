<?php

namespace Core\Base;

use Core\Support\Config;

class Support
{
    /**
     * Get config by file and key.
     *
     * @param $key
     * @return bool
     */
    public static function config($key, $default = '')
    {
        $config = new Config();
        return $config->get($key, $default);
    }

    /**
     * Get environment value.
     *
     * @param $key
     * @param string $default
     * @return mixed|string
     */
    public static function env($key, $default = '')
    {
        $config = new Config();
        $config->parseEnv();
        return $config->env($key, $default);
    }

    /**
     * Change url part to StudlyCaps for class name.
     * activity-report become ActivityReport
     * activity become Activity
     *
     * @param $string
     * @return mixed
     */
    public static function toStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Change url part to camelCase for method name.
     * save-user become saveUser
     * ajax-get-user become ajaxGetUser
     *
     * @param $string
     * @return string
     */
    public static function toCamelCase($string)
    {
        return lcfirst(self::toStudlyCaps($string));
    }
}