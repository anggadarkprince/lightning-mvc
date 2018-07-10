<?php

define('BASE_PATH', realpath(__DIR__ . '/../'));

/**
 * Set error handling
 * ----------------------------------------------------------------------
 * Bind some error handler to our custom handler of the app,
 * Logging of the exception types that will be reported as well.
 */

set_error_handler('\Core\Error::errorHandler');
set_exception_handler('\Core\Error::exceptionHandler');

/**
 * Initialize the app
 * ----------------------------------------------------------------------
 * Create new instance of lightning MVC wrapper, The kernels serve
 * the incoming requests to this application from web.
 */

$lightning = new \Core\Lightning();

return $lightning;