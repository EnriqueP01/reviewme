<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang');

Route::get('/dashboard', \App\Livewire\Feed::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');
    Route::get('/publish', \App\Livewire\PublishWorkflow::class)->name('publish');
    Route::get('/vibe/{postId}', \App\Livewire\VibeDetail::class)->name('vibe.detail');
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/github', [App\Http\Controllers\Auth\GithubAuthController::class, 'redirect'])->name('login.github');
Route::get('/auth/github/callback', [App\Http\Controllers\Auth\GithubAuthController::class, 'callback']);

require __DIR__.'/auth.php';
