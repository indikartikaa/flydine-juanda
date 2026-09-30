<?php
// Prevent directory listing and attach security headers
@header_remove('X-Powered-By');
http_response_code(404);
header("Content-Type: text/html; charset=UTF-8");
header("Content-Security-Policy: default-src 'none'; base-uri 'none'; form-action 'none'; frame-ancestors 'none';");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found</title>
</head>
<body>
    <h1>404 Not Found</h1>
    <p>The requested directory is not accessible.</p>
</body>
</html>
