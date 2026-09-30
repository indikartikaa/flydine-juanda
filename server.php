<?php

$publicPath = __DIR__.'/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$filePath = $publicPath.$uri;

// If the requested URI is a physical file in public, serve it with proper security headers
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'webp' => 'image/webp',
        'ico'  => 'image/x-icon',
        'css'  => 'text/css; charset=UTF-8',
        'js'   => 'text/javascript; charset=UTF-8',
        'json' => 'application/json',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
    ];

    $mime = $mimeTypes[$extension] ?? (mime_content_type($filePath) ?: 'application/octet-stream');

    header_remove('X-Powered-By');
    header('Content-Type: '.$mime);
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Length: '.filesize($filePath));
    readfile($filePath);
    exit;
}

$formattedDateTime = date('D M j H:i:s Y');
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$remoteAddress = ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1').':'.($_SERVER['REMOTE_PORT'] ?? '8000');

file_put_contents('php://stdout', "[$formattedDateTime] $remoteAddress [$requestMethod] URI: $uri\n");

header_remove('X-Powered-By');
require_once $publicPath.'/index.php';
