<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $handleCsrfMismatch = function ($request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi halaman telah berakhir, silakan muat ulang halaman dan coba lagi.'
                ], 419);
            }

            if ($request->is('logout')) {
                return redirect()->route('login');
            }

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation', '_token']))
                ->with('error', 'Sesi halaman telah berakhir, silakan coba lagi.');
        };

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) use ($handleCsrfMismatch) {
            if ($e->getStatusCode() === 419) {
                return $handleCsrfMismatch($request);
            }
        });
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) use ($handleCsrfMismatch) {
            return $handleCsrfMismatch($request);
        });
    })->create();
