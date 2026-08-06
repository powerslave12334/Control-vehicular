<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$publicDir = __DIR__.'/public';
if (! is_dir($publicDir)) {
    $publicDir = __DIR__.'/../public';
}

$path = urldecode($_SERVER['REQUEST_URI'] ?? '/');
$path = parse_url($path, PHP_URL_PATH);

if ($path !== '/' && $path !== null) {
    $candidate = realpath($publicDir.$path);
    $publicReal = realpath($publicDir);

    if ($publicReal !== false && $candidate !== false
        && str_starts_with($candidate, $publicReal.DIRECTORY_SEPARATOR)
        && is_file($candidate)) {
        $mime = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'json' => 'application/json',
            'txt' => 'text/plain',
            'map' => 'application/json',
        ];
        $ext = pathinfo($candidate, PATHINFO_EXTENSION);
        header('Content-Type: '.(array_key_exists($ext, $mime) ? $mime[$ext] : 'application/octet-stream'));
        header('Content-Length: '.filesize($candidate));
        readfile($candidate);
        exit;
    }
}

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);