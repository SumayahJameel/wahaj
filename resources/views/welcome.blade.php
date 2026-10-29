@extends('Layout.app')

@section('title', config('lantera.'.app()->getLocale().'.home').' | '.config('lantera.'.app()->getLocale().'.brand'))
@section('content')
    @php
        $locale = app()->getLocale();
        $t = fn ($key) => config("lantera.$locale.$key");
        $total = $quotes->total();
        $lanternIcon = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 1.75v1.5M10 21.25h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path class="lantern-body" d="M7.1 3.25h9.8a1.4 1.4 0 0 1 1.39 1.58l-1.55 11.9a1.4 1.4 0 0 1-1.39 1.22H8.65a1.4 1.4 0 0 1-1.39-1.22L5.71 4.83A1.4 1.4 0 0 1 7.1 3.25Z" stroke="currentColor" stroke-width="1.6"/><path class="lantern-flame" d="M12 7.6c1.55 1.45 2.3 2.75 2.3 3.85a2.3 2.3 0 0 1-4.6 0c0-1.1.75-2.4 2.3-3.85Z"/></svg>';
        $userIcon = '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>';
        $plusIcon = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>';
    @endphp

    <section class="hero" style="--hero-image: url('{{ asset('images/lake.jpg') }}');">
        <span class="sky-lantern" style="--x: 8%; --delay: 0s; --scale: 1;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 26%; --delay: -6s; --scale: .75;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 47%; --delay: -10s; --scale: .9;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 66%; --delay: -3s; --scale: .65;" aria-hidden="true"></span>
        <span class="sky-lantern" style="--x: 84%; --delay: -12s; --scale: 1.05;" aria-hidden="true"></span>
        <div class="hero-copy">
            <h1>{{ $t('hero_before') }} <span class="hero-highlight">{{ $t('hero_highlight') }}</span> {{ $t('hero_after') }}</h1>
            <p class="hero-subtitle">{{ $t('tagline') }}</p>
            <div class="hero-actions">
                @auth
                    <button type="button" popovertarget="quote-popup" class="ui-button button-gold button-lg">{!! $plusIcon !!}<span>{{ $t('share_quote') }}</span></button>
                @else
                    <a href="{{ route('login') }}" class="ui-button button-gold button-lg">{!! $plusIcon !!}<span>{{ $t('share_quote') }}</span></a>
                @endauth
                <a href="#quotes" class="ui-button button-glass button-lg">{{ $t('explore') }}<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M6 13l6 6 6-6"/></svg></a>
            </div>
        </div>
    </section>

    <section id="quotes" class="sky-section">
        @if ($errors->any())
            <div class="form-alert" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="feed-toolbar" data-sync="feed-toolbar">
            <div>
                <h2>{{ $t('today') }}</h2>
                <p class="section-hint">@auth{{ $t('sky_hint') }}@else{{ $t('sign_in_like') }}@endauth</p>
            </div>
            <div class="feed-tools">
                <span class="quote-count" data-sync="quote-count">{{ str_replace(':count', $total, $total === 1 ? $t('quote_count_one') : $t('quote_count')) }}</span>
                @if ($total > 1)
                    <nav class="segmented" aria-label="{{ $t('sort_label') }}">
                        <a href="{{ route('home') }}#quotes" class="segment" @if ($sort === 'newest') aria-current="page" @endif>{{ $t('sort_newest') }}</a>
                        <a href="{{ route('home', ['sort' => 'brightest']) }}#quotes" class="segment" @if ($sort === 'brightest') aria-current="page" @endif>{{ $t('sort_brightest') }}</a>
                    </nav>
                @endif
            </div>
        </div>

        <div class="quote-grid" data-sync="quote-grid">
            @forelse ($quotes as $quote)
                @php
                    $isMine = auth()->check() && $quote->user_id === auth()->id();
                    $returnQuoteId = $quotes->get($loop->index + 1)?->id ?? $quotes->get($loop->index - 1)?->id;
                    $cardId = 'quote-'.$quote->id;
                    $likeLabel = $quote->liked_by_user ? $t('unlike') : $t('like');
                    $isLatin = ! preg_match('/\p{Arabic}/u', $quote->content);
                @endphp
                <article id="{{ $cardId }}" class="quote-card {{ $quote->liked_by_user ? 'is-lit' : '' }}">
                    <span class="quote-mark" aria-hidden="true">“</span>
                    <blockquote class="quote-copy {{ $isLatin ? 'is-latin' : '' }}"><p dir="auto">{{ $quote->content }}</p></blockquote>
                    <footer class="quote-footer">
                        <p class="quote-meta">
                            <span class="quote-author">{!! $userIcon !!}<span dir="auto">{{ $quote->user->name }}</span></span>
                            <time class="quote-date" datetime="{{ $quote->created_at->toIso8601String() }}" title="{{ $quote->created_at->format('Y-m-d') }}">· {{ $quote->created_at->locale($locale)->diffForHumans() }}</time>
                        </p>
                        <div class="quote-actions">
                            @if ($isMine)
                                <button type="button" class="icon-button" data-action="edit" popovertarget="edit-{{ $cardId }}" aria-label="{{ $t('edit') }}" title="{{ $t('edit') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 5 4 4M4 20l4.2-.8L19 8.4a2.1 2.1 0 0 0-3-3L5.2 16.2 4 20Z"/></svg></button>
                                <button type="button" class="icon-button icon-danger" popovertarget="delete-{{ $cardId }}" aria-label="{{ $t('delete') }}" title="{{ $t('delete') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16m-10 4v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3"/></svg></button>
                            @endif
                            @auth
                                <form method="POST" action="{{ route('quotes.like', $quote) }}" class="action-form" data-swap="{{ $cardId }}" data-focus="[data-action=like]">
                                    @csrf
                                    <button type="submit" class="like-button {{ $quote->liked_by_user ? 'is-on' : '' }}" data-action="like" aria-pressed="{{ $quote->liked_by_user ? 'true' : 'false' }}" aria-label="{{ $likeLabel }}" title="{{ $likeLabel }}">{!! $lanternIcon !!}<span aria-hidden="true">{{ $quote->likes_count }}</span></button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="like-button" aria-label="{{ $t('sign_in_like') }}" title="{{ $t('sign_in_like') }}">{!! $lanternIcon !!}<span aria-hidden="true">{{ $quote->likes_count }}</span></a>
                            @endauth
                            <span class="sr-only">{{ $quote->likes_count }} {{ $quote->likes_count === 1 ? $t('like_one') : $t('likes') }}</span>
                        </div>
                    </footer>
                    @if ($isMine)
                        <x-quote-dialog
                            :id="'edit-'.$cardId"
                            :action="route('edit_quote', $quote)"
                            method="PUT"
                            :title="$t('edit_title')"
                            :subtitle="$t('edit_sub')"
                            :submit-label="$t('save')"
                            :content="$quote->content"
                            :swap="$cardId"
                        />
                        <div id="delete-{{ $cardId }}" popover class="lantera-popover confirm-popover" role="alertdialog" aria-labelledby="delete-title-{{ $quote->id }}">
                            <form method="POST" action="{{ route('delete_quote', $quote) }}" class="confirm-form" data-swap="{{ $cardId }}" data-swap-mode="grid">
                                @csrf @method('DELETE')
                                @if ($returnQuoteId)<input type="hidden" name="return_quote_id" value="{{ $returnQuoteId }}">@endif
                                <h2 id="delete-title-{{ $quote->id }}">{{ $t('delete_confirm') }}</h2>
                                <p>{{ $t('delete_confirm_hint') }}</p>
                                <div class="form-actions">
                                    <button type="button" class="ui-button button-light" popovertarget="delete-{{ $cardId }}" popovertargetaction="hide">{{ $t('cancel') }}</button>
                                    <button type="submit" class="ui-button button-danger">{{ $t('delete') }}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </article>
            @empty
                <div class="empty-state"><x-brand-lantern class="empty-lantern" /><p>{{ $t('empty_quotes') }}</p></div>
            @endforelse
        </div>

        {{ $quotes->onEachSide(1)->links('pagination.quotes') }}
    </section>

    @auth
        <button type="button" popovertarget="quote-popup" class="ui-button button-gold share-fab">{!! $plusIcon !!}<span>{{ $t('share_quote') }}</span></button>
        <x-quote-dialog
            id="quote-popup"
            :action="route('home')"
            :title="$t('share_light')"
            :subtitle="$t('share_sub')"
            :submit-label="$t('send')"
        />
    @endauth
@endsection
