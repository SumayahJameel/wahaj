<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0B0D22">
    <meta name="description" content="{{ config('lantera.'.app()->getLocale().'.brand') }} — {{ config('lantera.'.app()->getLocale().'.tagline') }}">
    <title>@yield('title', config('lantera.'.app()->getLocale().'.brand'))</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M12 1.75v1.5M10 21.25h4' stroke='%23FFB85C' stroke-width='1.5' stroke-linecap='round'/%3E%3Cpath d='M7.1 3.25h9.8a1.4 1.4 0 0 1 1.39 1.58l-1.55 11.9a1.4 1.4 0 0 1-1.39 1.22H8.65a1.4 1.4 0 0 1-1.39-1.22L5.71 4.83A1.4 1.4 0 0 1 7.1 3.25Z' fill='%2310132D' stroke='%23FFB85C' stroke-width='1.5'/%3E%3Cpath d='M12 7.6c1.55 1.45 2.3 2.75 2.3 3.85a2.3 2.3 0 0 1-4.6 0c0-1.1.75-2.4 2.3-3.85Z' fill='%23FFB85C'/%3E%3C/svg%3E">
    {{ \Illuminate\Support\Facades\Vite::fonts() }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased">
    <x-navbar :page="$page ?? ''" />
    <main>@yield('content')</main>
    @unless (in_array($page ?? '', ['Sign Up', 'Log In'], true))
    <footer class="site-footer" style="--footer-image: url('{{ asset('images/sunset.jpg') }}');">
        <div class="site-footer-inner">
            <x-brand small />
            <span>{{ config('lantera.'.app()->getLocale().'.footer') }}</span>
        </div>
    </footer>
    @endunless
</body>
</html>
