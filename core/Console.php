<?php

namespace Core;

use Core\Base\Support;
use Core\Console\GeneratorCommand;
use Core\Contract\MigrationCommand;

class Console
{
    static $foregroundColors = array(
        'bold' => '1', 'dim' => '2',
        'black' => '0;30', 'dark_gray' => '1;30',
        'blue' => '0;34', 'light_blue' => '1;34',
        'green' => '0;32', 'light_green' => '1;32',
        'cyan' => '0;36', 'light_cyan' => '1;36',
        'red' => '0;31', 'light_red' => '1;31',
        'purple' => '0;35', 'light_purple' => '1;35',
        'brown' => '0;33', 'yellow' => '1;33',
        'light_gray' => '0;37', 'white' => '1;37',
        'normal' => '0;39',
    );

    static $backgroundColors = array(
        'black' => '40', 'red' => '41',
        'green' => '42', 'yellow' => '43',
        'blue' => '44', 'magenta' => '45',
        'cyan' => '46', 'light_gray' => '47',
    );

    static $options = array(
        'underline' => '4', 'blink' => '5',
        'reverse' => '7', 'hidden' => '8',
    );
    static $EOF = "\n";

    /**
     * Logs a string to console.
     *
     * @param  string $str Input String
     * @param  string $color Text Color
     * @param  boolean $newline Append EOF?
     * @param  [type] $background Background Color
     * @return void [type] Formatted output
     */
    public static function log($str = '', $color = 'normal', $newline = true, $backgroundColor = null)
    {
        if (is_bool($color)) {
            $newline = $color;
            $color = 'normal';
        } elseif (is_string($color) && is_string($newline)) {
            $backgroundColor = $newline;
            $newline = true;
        }
        $str = $newline ? $str . self::$EOF : $str;
        echo self::$color($str, $backgroundColor);
    }

    /**
     * Catches static calls (Wildcard)
     * @param  string $foregroundColor Text Color
     * @param  array  $args             Options
     * @return string                   Colored string
     */
    public static function __callStatic($foregroundColor, $args)
    {
        $string         = $args[0];
        $coloredString = "";

        // Check if given foreground color found
        if( isset(self::$foregroundColors[$foregroundColor]) ) {
            $coloredString .= "\033[" . self::$foregroundColors[$foregroundColor] . "m";
        }
        else{
            die( $foregroundColor . ' not a valid color');
        }

        array_shift($args);
        foreach( $args as $option ){
            // Check if given background color found
            if(isset(self::$backgroundColors[$option])) {
                $coloredString .= "\033[" . self::$backgroundColors[$option] . "m";
            }
            elseif(isset(self::$options[$option])) {
                $coloredString .= "\033[" . self::$options[$option] . "m";
            }
        }

        // Add string and end coloring
        $coloredString .= $string . "\033[0m";

        return $coloredString;

    }
    
    /**
     * Run command application.
     */
    public function run()
    {
        $argv = $_SERVER['argv'];

        array_shift($argv); // strip the application name

        $this->parse($argv);
    }

    /**
     * Parse command and argument to select right action.
     *
     * @param $token
     */
    public function parse($token)
    {
        if (empty($token) || $token[0] == 'list') {
            $this->commandList();
        } else {
            $option = explode(':', $token[0]);
            if ($option[0] == 'generate') {
                $command = new GeneratorCommand($token);
                $command->execute();
            } elseif ($option[0] == 'migrate') {
                $command = new MigrationCommand($token);
                $command->execute();
            } elseif ($option[0] == 'help') {
                if (isset($token[1])) {
                    $commandClass = 'Core\Console\\' . Support::toStudlyCaps($token[1]) . 'Command';
                    if (class_exists($commandClass)) {
                        $command = new $commandClass($token);
                        $command->description();
                    }
                } else {
                    $this->commandList();
                }
            } else {
                Console::log("Invalid command option or argument, try run `lightning list`", 'red');
            }
        }
    }

    /**
     * Show command list.
     */
    public function commandList()
    {
        $content = <<<TOC
Lightning MVC - A hackable micro mvc boilerplate.
Author: Angga Ari Wijaya <anggadarkprince@gmail.com>

Usage:
    command [options] [arguments]
    
Available Commands:
    help   Display help for a command
    list   Lists commands
    
    generator
       generate:controller      Create a new controller class
       generate:filter          Create a new filter or middleware class
       generate:model           Create a new model class
       generate:migration       Create a new migration table class
    
    migration
       migrate                  Migrate database to current version
       migrate:rollback         Rollback the last database migration
       migrate:rebuild          Drop all tables and re-run the migrations      


TOC;
        Console::log($content);
    }

}