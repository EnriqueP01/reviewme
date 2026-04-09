<?php

use App\Http\Controllers\Auth\GithubAuthController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Feed;
use App\Livewire\Labs\GroupManager;
use App\Livewire\Profile;
use App\Livewire\PublishWorkflow;
use App\Livewire\VibeDetail;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dev/login', function () {
    if (app()->environment('local')) {
        $user = User::first();
        if (! $user) {
            $user = User::factory()->create();
        }
        auth()->login($user);

        return redirect()->route('dashboard');
    }
    abort(403);
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session()->put('locale', $locale);
    }

    return redirect()->back();
})->name('lang');

Route::get('/dashboard', Feed::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/publish', PublishWorkflow::class)->name('publish');
    Route::get('/labs', GroupManager::class)->name('labs');
    Route::get('/vibe/{postId}', VibeDetail::class)->name('vibe.detail');
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/github', [GithubAuthController::class, 'redirect'])->name('login.github');
Route::get('/auth/github/callback', [GithubAuthController::class, 'callback']);

require __DIR__.'/auth.php';
