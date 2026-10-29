<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuoteController::class, 'index'])->name('home');

Route::post('/locale', function (Request $request) {
    $validated = $request->validate(['locale' => ['required', 'in:en,ar']]);
    $request->session()->put('locale', $validated['locale']);

    return redirect()->back();
})->name('locale.update');

Route::get('/signup', [function () {
    return view('Authentication.SignUp', ['page' => 'Sign Up']);
}]);
Route::post('/signup', [SignupController::class, 'signup']);

Route::get('/login', function () {
    return view('Authentication.LogIn', ['page' => 'Log In']);
})->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

Route::post('/', [QuoteController::class, 'store']);

Route::put('/quote/{quote}', [QuoteController::class, 'update'])->name('edit_quote');
Route::delete('/quote/{quote}', [QuoteController::class, 'destroy'])->name('delete_quote');

Route::post('/quote/{quote}/like', [QuoteController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('quotes.like');
