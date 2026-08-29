<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<div style="background:#09090b;color:#f87171;padding:2rem;font-family:sans-serif;border:1px solid #dc2626;margin:2rem;border-radius:8px;">';
    echo '<h2 style="color:#ef4444;margin-top:0;">🛑 Application Error Caught</h2>';
    echo '<p style="font-size:1.1rem;color:#fff;font-weight:bold;">' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre style="background:#18181b;color:#a1a1aa;padding:1rem;overflow:auto;border-radius:4px;font-size:0.85rem;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
}
