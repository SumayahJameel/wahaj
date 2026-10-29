<?php

use App\Models\Quote;
use App\Models\User;

test('guests see every quote with its author and date but no edit or delete controls', function () {
    $author = User::factory()->create(['name' => 'Noura']);
    $quote = $author->quotes()->create(['content' => 'Light travels far']);

    $this->get('/')
        ->assertOk()
        ->assertSee('Light travels far')
        ->assertSee('Noura')
        ->assertSee($quote->created_at->toIso8601String(), false)
        ->assertDontSee('edit-quote-'.$quote->id, false)
        ->assertDontSee('delete-quote-'.$quote->id, false);
});

test('a user can log in with the right password and stays a guest with the wrong one', function () {
    $user = User::factory()->create(['email' => 'reader@example.com', 'password' => 'password123']);

    $this->from('/login')->post('/login', ['email' => 'reader@example.com', 'password' => 'wrong-password'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');
    $this->assertGuest();

    $this->post('/login', ['email' => 'reader@example.com', 'password' => 'password123'])
        ->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('logging out returns the visitor to the guest view', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('logout'))->assertRedirect(route('home'));

    $this->assertGuest();
});

test('a quote outside 3 to 155 characters is not shared', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/', ['content' => 'ab'])->assertSessionHasErrors('content');
    $this->actingAs($user)->post('/', ['content' => str_repeat('a', 156)])->assertSessionHasErrors('content');
    $this->actingAs($user)->post('/', ['content' => 'Just right'])->assertRedirect();

    expect(Quote::pluck('content')->all())->toBe(['Just right']);
});

test('deleting a user also deletes their quotes', function () {
    $user = User::factory()->create();
    $user->quotes()->create(['content' => 'Gone with the account']);

    $user->delete();

    $this->assertDatabaseCount('quotes', 0);
});

test('the site opens in Arabic by default and can switch to English', function () {
    $this->get('/')->assertOk()->assertSee('<html lang="ar" dir="rtl">', false);

    $this->post(route('locale.update'), ['locale' => 'en'])->assertRedirect();

    $this->get('/')->assertSee('<html lang="en" dir="ltr">', false);
});
