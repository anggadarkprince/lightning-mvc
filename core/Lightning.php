<?php

namespace Core;


use Core\Base\Router;

class Lightning
{
    /**
     * Run lightning MVC.
     *
     * @throws \Exception
     */
    public function run()
    {
        if (PHP_SAPI === 'cli') {
            $console = new Console();
            $console->run();
        } else {
            $router = new Router();
            require __DIR__ . '/../app/Routes/app.php';
            $router->dispatch($_SERVER['QUERY_STRING']);
        }
    }
}