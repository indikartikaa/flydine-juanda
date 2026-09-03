<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBlocked
{
    public function handle(Request $request, Closure $next): Response
    {

        if (
            auth()->check() &&
            auth()->user()->is_blocked
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akun Anda sementara diblokir karena aktivitas transaksi mencurigakan.'
                );

        }


        return $next($request);

    }
}
