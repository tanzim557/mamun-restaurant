<?php

/**
 * Laravel Entry Point for Shared Hosting (InfinityFree / LiteSpeed / cPanel)
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// If the file exists directly in public/, let the server serve it
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

// Otherwise load the Laravel public index.php
require_once __DIR__ . '/public/index.php';
