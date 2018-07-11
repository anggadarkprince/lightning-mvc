<?php

namespace Core\Contract;


use Core\Console;

class MigrationCommand implements CommandInterface
{

    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function execute()
    {

    }

    public function description()
    {
        $content = <<<TOC
Usage: Create and run database migration
    
    migrate              Run the database migrations
    migrate:rollback     Rollback the last database migration
    migrate:rebuild      Drop all tables and re-run the migrations
         

TOC;
        Console::log($content);
    }
}