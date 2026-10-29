<?php

use App\Models\User;

test('a guest can sign up with a letters-only name and the password is stored hashed', function () {
    $this->post('/signup', [
        'name' => 'سمية جميل',
        'email' => 'sumayah@example.com',
        'password' => 'password123',
    ])->assertRedirect(route('home'));

    $user = User::where('email', 'sumayah@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->password)->not->toBe('password123');
    $this->assertAuthenticatedAs($user);
});

test('a name with numbers or symbols is rejected', function () {
    $this->from('/signup')->post('/signup', [
        'name' => 'sara123',
        'email' => 'sara@example.com',
        'password' => 'password123',
    ])->assertRedirect('/signup')->assertSessionHasErrors('name');

    $this->assertDatabaseMissing('users', ['email' => 'sara@example.com']);
});

test('only the owner can edit or delete a quote', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create();
    $quote = $owner->quotes()->create(['content' => 'Mine to keep']);

    $this->actingAs($stranger)->put(route('edit_quote', $quote), ['content' => 'Not yours'])->assertForbidden();
    $this->actingAs($stranger)->delete(route('delete_quote', $quote))->assertForbidden();
    $this->assertDatabaseHas('quotes', ['id' => $quote->id, 'content' => 'Mine to keep']);
});
