@extends('Layout.AuthImg')

@php($t = fn ($key) => config('lantera.'.app()->getLocale().'.'.$key))
@section('title', $t('create_account').' | '.$t('brand'))

@section('form')
    <form method="post" action="/signup" class="auth-fields">
        @csrf
        <div class="auth-heading">
            <p class="eyebrow">{{ $t('join_community') }}</p>
            <h2>{{ $t('signup_heading') }}</h2>
        </div>
        <div class="field">
            <label for="name" class="label">{{ $t('name') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" class="input" dir="auto" required minlength="2" maxlength="50" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
            <p class="field-hint">{{ $t('name_hint') }}</p>
            @error('name')<p id="name-error" class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="email" class="label">{{ $t('email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="input" dir="ltr" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            <p class="field-hint">{{ $t('email_signup_hint') }}</p>
            @error('email')<p id="email-error" class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="password" class="label">{{ $t('password') }}</label>
            <input id="password" name="password" type="password" autocomplete="new-password" class="input" dir="ltr" required minlength="8" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
            <p class="field-hint">{{ $t('password_hint') }}</p>
            @error('password')<p id="password-error" class="field-error">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="ui-button button-gold button-lg w-full">{{ $t('create_account') }}</button>
        <p class="auth-switch">{{ $t('already_account') }} <a href="/login">{{ $t('login') }}</a></p>
    </form>
@endsection
