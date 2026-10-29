<?php

namespace App\Http;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale', 'ar');
        app()->setLocale(in_array($locale, ['en', 'ar'], true) ? $locale : 'ar');

        return $next($request);
    }
}
