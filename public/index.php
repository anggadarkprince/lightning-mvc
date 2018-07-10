<?php

/**
 * Lightning MVC - A hackable micro mvc boilerplate
 *
 * @package  Lightning MVC
 * @author   Angga Ari Wijaya <anggadarkprince@gmail.com>
 */

/**
 * Register composer auto loader
 * ----------------------------------------------------------------------
 * Composer provides a convenient autoloader library and our package,
 * We'll simply require it and automatic included in lightning MVC scope.
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Register composer auto loader
 * ----------------------------------------------------------------------
 * Initialize our app by return router from bootstrap file,
 * and send the associated response back to client.
 */
$lightning = require_once __DIR__ . '/../app/bootstrap.php';

$lightning->run();