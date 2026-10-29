<?php

use App\Http\Controllers\QuoteController;
use App\Models\Quote;
use App\Models\User;

test('the feed paginates quotes newest first without losing or repeating any', function () {
    $author = User::factory()->create();
    $total = QuoteController::PER_PAGE + 3;

    foreach (range(1, $total) as $number) {
        $author->quotes()->create(['content' => "Quote number {$number}"]);
    }

    $firstPage = $this->get('/')->assertOk()->viewData('quotes');
    $secondPage = $this->get('/?page=2')->assertOk()->viewData('quotes');

    expect($firstPage->count())->toBe(QuoteController::PER_PAGE)
        ->and($secondPage->count())->toBe(3)
        ->and($firstPage->first()->content)->toBe("Quote number {$total}");

    $seen = $firstPage->pluck('id')->merge($secondPage->pluck('id'));

    expect($seen->unique()->count())->toBe($total)
        ->and($seen->sort()->values()->all())->toBe(Quote::orderBy('id')->pluck('id')->all());
});

test('the brightest sort orders quotes by their likes', function () {
    $author = User::factory()->create();
    $fan = User::factory()->create();
    $quiet = $author->quotes()->create(['content' => 'A quiet quote']);
    $bright = $author->quotes()->create(['content' => 'A bright quote']);
    $quiet->touch();
    $fan->likedQuotes()->attach($bright);

    $quotes = $this->get('/?sort=brightest')->assertOk()->viewData('quotes');

    expect($quotes->first()->id)->toBe($bright->id);
});

test('a page past the end redirects to the last page', function () {
    $author = User::factory()->create();
    $author->quotes()->create(['content' => 'Only quote']);

    $this->get('/?page=5')->assertRedirect('/?page=1');
});

test('liking a quote returns the visitor to the page they were on', function () {
    $user = User::factory()->create();
    $quote = $user->quotes()->create(['content' => 'A quote worth sharing']);

    $this->actingAs($user)
        ->from('/?page=2&sort=brightest')
        ->post(route('quotes.like', $quote))
        ->assertRedirect('/?sort=brightest&page=2#quote-'.$quote->id);
});
