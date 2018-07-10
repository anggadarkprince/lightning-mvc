<?php

/**
 * Route app
 * @property \Core\Base\Router $router
 */

$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->add('/', ['controller' => 'Home', 'action' => 'index']);
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);