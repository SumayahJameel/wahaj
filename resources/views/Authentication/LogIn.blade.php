@extends('Layout.AuthImg')

@php($t = fn ($key) => config('lantera.'.app()->getLocale().'.'.$key))
@section('title', $t('login').' | '.$t('brand'))

@section('form')
    <form method="post" action="{{ route('login') }}" class="auth-fields">
        @csrf
        <div class="auth-heading">
            <p class="eyebrow">{{ $t('welcome_back') }}</p>
            <h2>{{ $t('login_heading') }}</h2>
        </div>
        <div class="field">
            <label for="email" class="label">{{ $t('email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="input" dir="ltr" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            <p class="field-hint">{{ $t('email_hint') }}</p>
            @error('email')<p id="email-error" class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="password" class="label">{{ $t('password') }}</label>
            <input id="password" name="password" type="password" autocomplete="current-password" class="input" dir="ltr" required minlength="8" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
            <p class="field-hint">{{ $t('password_hint') }}</p>
            @error('password')<p id="password-error" class="field-error">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="ui-button button-gold button-lg w-full">{{ $t('login') }}</button>
        <p class="auth-switch">{{ $t('new_to') }} <a href="/signup">{{ $t('create_account') }}</a></p>
    </form>
@endsection
