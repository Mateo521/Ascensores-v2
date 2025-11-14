<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__ . '/../ascensores-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../ascensores-app/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__ . '/../ascensores-app/bootstrap/app.php';

// IMPORTANTE: dile a Laravel que el public path es public_html
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
