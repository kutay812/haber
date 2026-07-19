<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Login as admin
$admin = \App\Models\User::role('Super Admin')->first();
if (!$admin) {
    echo "No admin found.";
    exit;
}
\Illuminate\Support\Facades\Auth::login($admin);

// Try to hit /admin/logout
$request = \Illuminate\Http\Request::create('/admin/logout', 'POST', [
    '_token' => csrf_token()
]);
// Session might be needed
$request->setLaravelSession(app('session.store'));
app('session')->start();
$request->session()->put('_token', csrf_token());

$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent();
