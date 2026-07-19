<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = \Illuminate\Http\Request::create('/admin/logout', 'POST', [
    '_token' => 'dummy'
]);
// We don't have a valid CSRF token, so this will throw 419 normally.
// But we excluded 'api/*' in CSRF. Let's create an api route just to test role middleware order.
