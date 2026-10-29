@props([
    'id',
    'action',
    'method' => 'POST',
    'title',
    'subtitle',
    'submitLabel',
    'content' => '',
    'swap' => null,
])

@php
    $t = fn ($key) => config('lantera.'.app()->getLocale().'.'.$key);
@endphp

<div id="{{ $id }}" popover class="lantera-popover compose-dialog" role="dialog" aria-labelledby="{{ $id }}-title">
    <form method="POST" action="{{ $action }}" class="compose-form" data-compose @if ($swap) data-swap="{{ $swap }}" data-keep-on-error data-focus="[data-action=edit]" @else data-create @endif>
        @csrf
        @if (strtoupper($method) !== 'POST')
            @method($method)
        @endif
        <div class="dialog-heading">
            <div>
                <h2 id="{{ $id }}-title">{{ $title }}</h2>
                <p>{{ $subtitle }}</p>
            </div>
            <button type="button" popovertarget="{{ $id }}" popovertargetaction="hide" class="icon-button" aria-label="{{ $t('close') }}" title="{{ $t('close') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>
        <label for="{{ $id }}-content" class="label">{{ $t('your_quote') }}</label>
        <textarea id="{{ $id }}-content" name="content" rows="5" required minlength="3" maxlength="155" placeholder="{{ $t('write_quote') }}" class="input input-quote" dir="auto" data-compose-input>{{ $content }}</textarea>
        <div class="compose-meta">
            <span>{{ $t('characters') }}</span>
            <span class="compose-counter" data-compose-counter>0 / 155</span>
        </div>
        <div class="compose-progress" aria-hidden="true"><span data-compose-progress></span></div>
        <div class="form-actions dialog-actions">
            <button type="button" class="ui-button button-light" popovertarget="{{ $id }}" popovertargetaction="hide">{{ $t('cancel') }}</button>
            <button type="submit" class="ui-button button-primary">
                @if ($swap)
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                @else
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                @endif
                <span>{{ $submitLabel }}</span>
            </button>
        </div>
    </form>
    <div class="compose-preview" aria-hidden="true">
        <p class="compose-preview-label">{{ $t('preview_label') }}</p>
        <div class="compose-lantern"><i></i><x-brand-lantern /></div>
        <div class="quote-card is-lit compose-card">
            <span class="quote-mark">“</span>
            <blockquote class="quote-copy"><p dir="auto" data-compose-preview data-empty="{{ $t('preview_empty') }}">{{ $content !== '' ? $content : $t('preview_empty') }}</p></blockquote>
            <footer class="quote-footer">
                <p class="quote-meta">
                    <span class="quote-author"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg><span dir="auto">{{ auth()->user()?->name }}</span></span>
                    <span class="quote-date">· {{ $t('now') }}</span>
                </p>
            </footer>
        </div>
    </div>
</div>
