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

}