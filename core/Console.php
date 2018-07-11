<?php

namespace Core;


use Core\Base\Support;
use Core\Console\GeneratorCommand;
use Core\Contract\MigrationCommand;

class Console
{
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
            if($option[0] == 'generate') {
                $command = new GeneratorCommand($token);
                $command->execute();
            } elseif($option[0] == 'migrate') {
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
                echo "Invalid command option or argument, try run `lightning list`\n";
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
        echo $content;
    }

}