<?php

use Core\Base\Support;

return [

    'name' => Support::env('APP_NAME'),

    'environment' => Support::env('APP_ENVIRONMENT'),

    'url' => Support::env('APP_URL'),

];