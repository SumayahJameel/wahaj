@props(['small' => false])

<span {{ $attributes->merge(['class' => 'brand-lockup'.($small ? ' brand-lockup-small' : '')]) }}>
    <x-brand-lantern />
    <span class="brand-name">{{ config('lantera.'.app()->getLocale().'.brand') }}</span>
</span>
