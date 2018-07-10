<?php

use Core\Base\Support;

return [

    'driver' => Support::env('DB_DRIVER'),

    'host' => Support::env('DB_HOST'),

    'port' => Support::env('DB_PORT'),

    'database' => Support::env('DB_DATABASE'),

    'username' => Support::env('DB_USERNAME'),

    'password' => Support::env('DB_PASSWORD'),

];