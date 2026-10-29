@extends('Layout.app')

@section('content')
    <section class="auth-page" style="--auth-image: url('{{ asset('images/sky.jpg') }}');">
        <span class="sky-lantern" style="--x: 6%; --delay: 0s; --scale: 1;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 22%; --delay: -7s; --scale: .8;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 50%; --delay: -12s; --scale: .9;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 78%; --delay: -4s; --scale: .7;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 93%; --delay: -15s; --scale: 1;" aria-hidden="true"></span>
        <div class="auth-shell">
            <div class="auth-form">
                @yield('form')
            </div>
            <aside class="auth-art" aria-label="{{ config('lantera.'.app()->getLocale().'.brand') }}">
                <div class="auth-art-lantern" aria-hidden="true"><i></i><x-brand-lantern /></div>
                <p class="eyebrow">{{ config('lantera.'.app()->getLocale().'.brand') }}</p>
                <p class="auth-art-copy">{{ config('lantera.'.app()->getLocale().'.auth_art') }}</p>
            </aside>
        </div>
    </section>
@endsection
