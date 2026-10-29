@props(['page' => ''])

@php
    $locale = app()->getLocale();
    $text = fn ($key) => config("lantera.$locale.$key");
    $authPage = in_array($page, ['Sign Up', 'Log In'], true);
@endphp

<nav class="site-nav">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="brand-link" aria-label="{{ $text('brand_home') }}">
            <x-brand />
        </a>
        @unless ($authPage)
            <span class="nav-divider" aria-hidden="true"></span>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link" @if (request()->routeIs('home')) aria-current="page" @endif>{{ $text('home') }}</a>
                <a href="{{ route('home') }}#quotes" class="nav-link">{{ $text('quotes') }}</a>
            </div>
        @endunless
        <div class="nav-actions">
            <form method="post" action="{{ route('locale.update') }}" class="header-control language-switch" role="group" aria-label="{{ $text('language_label') }}" data-keep-scroll>
                @csrf
                <button type="submit" name="locale" value="ar" lang="ar" class="language-option" aria-pressed="{{ $locale === 'ar' ? 'true' : 'false' }}" title="العربية">ع</button>
                <button type="submit" name="locale" value="en" lang="en" class="language-option" aria-pressed="{{ $locale === 'en' ? 'true' : 'false' }}" title="English">EN</button>
            </form>
            @unless ($authPage)
                @auth
                    <span class="header-control user-chip">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                        <span class="user-name" dir="auto">{{ auth()->user()->name }}</span>
                    </span>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="header-control header-icon" aria-label="{{ $text('logout') }}" title="{{ $text('logout') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4M9 8l-4 4 4 4M5 12h11"/></svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="header-control header-link nav-login">{{ $text('login') }}</a>
                    <a href="/signup" class="ui-button button-gold header-cta">{{ $text('signup') }}</a>
                @endauth
            @endunless
        </div>
    </div>
</nav>
