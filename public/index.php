<?php
declare(strict_types=1);
$app=require dirname(__DIR__).'/bootstrap/app.php';
header('X-Content-Type-Options: nosniff');header('Referrer-Policy: strict-origin-when-cross-origin');header('X-Frame-Options: DENY');header('Permissions-Policy: camera=(), microphone=(), geolocation=()');header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; style-src 'self'; script-src 'self'; font-src 'self'; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
$app->run();
