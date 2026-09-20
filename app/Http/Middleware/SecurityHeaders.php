<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Mencegah halaman ini di-embed oleh iframe di situs lain (anti-clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');

        // Mencegah browser menebak tipe konten (anti MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Aktifkan perlindungan XSS bawaan browser
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Batasi informasi Referer yang dikirim ke situs lain
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Batasi penggunaan fitur browser sensitif
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Hapus header bawaan Laravel yang memberi tahu situs dibuat dengan PHP/Laravel
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
