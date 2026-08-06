<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response instanceof Response) {
            return $response;
        }

        foreach ($this->securityHeaders() as $name => $value) {
            $response->headers->set($name, $value);
        }

        return $response;
    }

    /**
     * Cabeceras de seguridad que aplica el middleware. Es la fuente de verdad
     * que el SecurityPanel usa como respaldo cuando el escaneo HTTP en vivo
     * no está disponible (p. ej. servidor de desarrollo de un solo hilo).
     */
    public function securityHeaders(): array
    {
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://maps.googleapis.com https://*.googleapis.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data: blob: https://*.googleapis.com https://*.gstatic.com",
            "font-src 'self' data: https://fonts.gstatic.com",
            "connect-src 'self' https://*.googleapis.com",
            'frame-src https://maps.google.com https://*.google.com',
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $hsts = 'max-age=31536000';
        if (config('app.force_https', false) || config('app.env') === 'production') {
            $hsts .= '; includeSubDomains';
        }

        return [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Content-Security-Policy' => $csp,
            'Strict-Transport-Security' => $hsts,
        ];
    }
}
