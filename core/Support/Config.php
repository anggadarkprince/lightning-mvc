<?php

namespace Core\Support;


class Config
{
    static $environments = null;

    /**
     * Parse environment data.
     *
     * @param string $file
     */
    public function parseEnv($file = '')
    {
        if (self::$environments === null) {

            if (empty($file)) {
                $file = BASE_PATH . '/.env';
            }

            $streamFile = fopen($file, 'r');
            $theData = fread($streamFile, filesize($file));

            $configs = [];
            $lines = explode("\n", $theData);
            foreach ($lines as $line) {
                if(!empty(trim($line))) {
                    $tmp = explode("=", $line);
                    $configs[$tmp[0]] = $tmp[1];
                }
            }
            fclose($streamFile);

            self::$environments = $configs;
        }
    }

    /**
     * Get environment value.
     *
     * @param $key
     * @param string $default
     * @return mixed|string
     */
    public function env($key, $default = '')
    {
        return key_exists($key, self::$environments) ? self::$environments[$key] : $default;
    }

    /**
     * Get config data.
     *
     * @param $key
     * @param string $default
     * @return string
     */
    public function get($key, $default = '')
    {
        $configKeys = explode('.', $key);

        $setting = BASE_PATH . '/app/Config/' . $configKeys[0] . '.php';
        if (is_file($setting)) {
            $configs = include $setting;

            return key_exists($configKeys[1], $configs) ? $configs[$configKeys[1]] : $default;
        }
        return $default;
    }

}