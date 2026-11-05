<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['id', 'en'];

        $locale = $request->query('locale') ?? $request->query('lang') ?? $request->header('X-Locale');

        if (! $locale && $request->header('Accept-Language')) {
            $preferred = $request->getPreferredLanguage($supportedLocales);
            if ($preferred) {
                $locale = str_starts_with($preferred, 'en') ? 'en' : 'id';
            }
        }

        if ($locale && in_array($locale, $supportedLocales, true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
