<?php

use App\Http\Controllers\Auth\GithubAuthController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Changelog;
use App\Livewire\Documentation;
use App\Livewire\Feed;
use App\Livewire\Groups\GroupManager;
use App\Livewire\Leaderboard;
use App\Livewire\Legal;
use App\Livewire\PostDetail;
use App\Livewire\Profile;
use App\Livewire\PublishWorkflow;
use App\Livewire\Status;
use App\Livewire\UpdatePost;
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
    Route::get('/profile', function () {
        if (! auth()->user()->handle) {
            auth()->user()->update(['handle' => 'user-'.auth()->id()]);
        }
        return redirect()->route('profile.show', auth()->user()->handle);
    })->name('profile');

    Route::get('/publish', PublishWorkflow::class)->name('publish');
    Route::get('/groups', GroupManager::class)->middleware('karma:group.create')->name('groups');
    Route::get('/posts/{postId}', PostDetail::class)->name('posts.detail');
    Route::get('/posts/{postId}/update', UpdatePost::class)->name('posts.update');
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/profile/{handle}', Profile::class)->name('profile.show');

Route::get('/auth/github', [GithubAuthController::class, 'redirect'])->name('login.github');
Route::get('/auth/github/callback', [GithubAuthController::class, 'callback']);

// Support & Info (Public)
Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');
Route::get('/docs', Documentation::class)->name('docs');
Route::get('/changelog', Changelog::class)->name('changelog');
Route::get('/status', Status::class)->name('status');
Route::get('/privacy', Legal::class)->defaults('type', 'privacy')->name('privacy');
Route::get('/terms', Legal::class)->defaults('type', 'terms')->name('terms');

require __DIR__.'/auth.php';
