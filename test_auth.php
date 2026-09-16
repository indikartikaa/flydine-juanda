<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::where('email', 'admin.ops@flydine.test')->first();
if (!$user) { echo "User not found\n"; exit; }

auth()->login($user);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/dashboard', 'GET')
);

echo 'URL /dashboard Status: ' . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 302) {
    echo 'Redirect to: ' . $response->headers->get('Location') . "\n";
    
    // Test the redirect destination
    $redirectUrl = parse_url($response->headers->get('Location'), PHP_URL_PATH);
    $response2 = $kernel->handle(
        $request2 = Illuminate\Http\Request::create($redirectUrl, 'GET')
    );
    echo 'URL ' . $redirectUrl . ' Status: ' . $response2->getStatusCode() . "\n";
}
