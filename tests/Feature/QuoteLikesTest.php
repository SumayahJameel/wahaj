<?php

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\QueryException;

function makeQuoteForLikes(): Quote
{
    $author = User::factory()->create();

    return $author->quotes()->create(['content' => 'A quote worth sharing']);
}

test('guests can see the public like count but cannot like a quote', function () {
    $quote = makeQuoteForLikes();

    $this->withSession(['locale' => 'en'])->get('/')->assertOk()->assertSee('0 likes');
    $this->post(route('quotes.like', $quote))->assertRedirect('/login');
    $this->assertDatabaseCount('likes', 0);
});

test('an authenticated user can like and unlike a quote', function () {
    $user = User::factory()->create();
    $quote = makeQuoteForLikes();

    $this->actingAs($user)->post(route('quotes.like', $quote))->assertRedirect();
    $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'quote_id' => $quote->id]);
    $this->withSession(['locale' => 'en'])->get('/')->assertSee('1 like')->assertSee('Unlike');

    $this->post(route('quotes.like', $quote))->assertRedirect();
    $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'quote_id' => $quote->id]);
});

test('a quote deletion cascades to its likes', function () {
    $user = User::factory()->create();
    $quote = makeQuoteForLikes();
    $user->likedQuotes()->attach($quote);

    $quote->delete();

    $this->assertDatabaseCount('likes', 0);
});

test('a user deletion cascades to their likes', function () {
    $user = User::factory()->create();
    $quote = makeQuoteForLikes();
    $user->likedQuotes()->attach($quote);

    $user->delete();

    $this->assertDatabaseCount('likes', 0);
});

test('the database unique constraint prevents a user liking a quote twice', function () {
    $user = User::factory()->create();
    $quote = makeQuoteForLikes();
    $user->likedQuotes()->attach($quote);

    expect(fn () => $user->likedQuotes()->attach($quote))
        ->toThrow(QueryException::class);
});
