<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/auth/github', [GitHubController::class, 'redirect'])->name('auth.github');
Route::get('/auth/github/callback', [GitHubController::class, 'callback'])->name('auth.github.callback');

Route::resource('posts', PostController::class);

Route::get('/oauth/callback', function () {
    $posts = \App\Models\Post::with('author')->latest()->paginate(10);
    return view('posts.index', compact('posts'));
})->name('oauth.callback');

Route::post('/oauth/token', [\Laravel\Passport\Http\Controllers\AccessTokenController::class, 'issueToken'])
    ->middleware('throttle')
    ->name('passport.token');

Route::get('/oauth/authorize', [\Laravel\Passport\Http\Controllers\AuthorizationController::class, 'authorize'])
    ->middleware(['auth'])
    ->name('passport.authorize');

Route::post('/oauth/authorize', [\Laravel\Passport\Http\Controllers\ApproveAuthorizationController::class, 'approve'])
    ->middleware(['auth'])
    ->name('passport.authorizations.approve');

Route::delete('/oauth/authorize', [\Laravel\Passport\Http\Controllers\DenyAuthorizationController::class, 'deny'])
    ->middleware(['auth'])
    ->name('passport.authorizations.deny');