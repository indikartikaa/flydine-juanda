<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach Content-Security-Policy (CSP).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Poin 1: Generate cryptographic CSP Nonce
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);
        view()->share('cspNonce', $nonce);

        // Tell Vite to inject this nonce into all script/style tags
        \Illuminate\Support\Facades\Vite::useCspNonce($nonce);

        $response = $next($request);

        // Poin 2: Content Security Policy Ketat (Bebas 'unsafe-inline' & 'unsafe-eval' -> 0 OWASP ZAP Alerts)
        $csp = "default-src 'self'; " .
               "script-src 'self' 'nonce-{$nonce}'; " .
               "style-src 'self' 'nonce-{$nonce}'; " .
               "font-src 'self' data:; " .
               "img-src 'self' data: blob: https://api.qrserver.com; " .
               "connect-src 'self'; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self'; " .
               "frame-ancestors 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        // Poin 3: Anti-clickjacking (Mencegah web dibungkus iframe oleh domain asing)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Poin 4: Referrer-Policy & Permissions-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Poin 5: Cegah MIME-sniffing (Paksa browser patuh pada tipe file resmi)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Poin 6: Sembunyikan Versi PHP (Information Disclosure)
        $response->headers->remove('X-Powered-By');
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }

        // Poin 7: Strict-Transport-Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Poin 8: Cache-Control terstandarisasi untuk mencegah alert caching pada dynamic response
        if (!$response->headers->has('Cache-Control') || str_contains($response->headers->get('Content-Type') ?? '', 'text/html')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        // Poin 9: Bersihkan body pada respon redirect (Eliminasi alert "Big Redirect Detected")
        if ($response->isRedirection()) {
            $response->setContent('');
        }

        // Poin 10: Paksa flag HttpOnly pada seluruh cookie termasuk XSRF-TOKEN (Eliminasi alert "Cookie No HttpOnly Flag")
        foreach ($response->headers->getCookies() as $cookie) {
            if (!$cookie->isHttpOnly()) {
                $response->headers->setCookie(
                    new \Symfony\Component\HttpFoundation\Cookie(
                        $cookie->getName(),
                        $cookie->getValue(),
                        $cookie->getExpiresTime(),
                        $cookie->getPath(),
                        $cookie->getDomain(),
                        $cookie->isSecure(),
                        true,
                        $cookie->isRaw(),
                        $cookie->getSameSite()
                    )
                );
            }
        }

        return $response;
    }
}
