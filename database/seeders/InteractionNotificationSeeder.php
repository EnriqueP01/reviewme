<?php

namespace Database\Seeders;

use App\Actions\Comments\StorePostCommentAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Actions\Reviews\StoreFullReviewAction;
use App\Actions\Reviews\StoreReviewAction;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class InteractionNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Target User
        $me = User::where('email', 'enriquepuertopro0101@gmail.com')->first();
        if (!$me) return;

        // 2. Personas
        $thomas = User::where('email', 'thomas@reviewme.io')->first();
        $julie = User::where('email', 'julie@reviewme.io')->first();
        $kevin = User::where('email', 'kevin@reviewme.io')->first();
        $sophie = User::where('email', 'sophie@reviewme.io')->first();

        // Ensure we have a post to interact with
        $post = $me->posts()->latest()->first();
        if (!$post) {
            $post = Post::create([
                'user_id' => $me->id,
                'title' => 'My New Architecture Proposal',
                'description' => 'Working on a new way to handle notifications and SPA transitions.',
                'visibility' => 'public',
            ]);
        }

        // Ensure we have a snippet to interact with
        $snippet = $post->snippets()->first();
        if (!$snippet) {
            $snippet = \App\Models\Snippet::create([
                'post_id' => $post->id,
                'code_content' => "// Notification Logic\n\$user->notify(new GeneralNotification(...));",
                'language' => 'php',
                'version_number' => 1
            ]);
        }

        $commentAction = app(StorePostCommentAction::class);
        $reactionAction = app(ToggleReactionAction::class);
        $fullReviewAction = app(StoreFullReviewAction::class);
        $quickReviewAction = app(StoreReviewAction::class);

        // -- ACTION 1: Thomas comments on the post --
        $comment = $commentAction->execute($thomas, $post->id, "This is exactly what we needed for the 2026 update. Great job Enrique!");

        // -- ACTION 2: Julie replies to Thomas's comment --
        $commentAction->execute($julie, $post->id, "I agree with Thomas, but we should double check the CSRF protection on SPA redirects.", $comment->id);

        // -- ACTION 3: Kevin reacts 'Mindblown' to the post --
        $reactionAction->execute($kevin, $post, 'mindblown');

        // -- ACTION 4: Sophie performs a Full Review --
        $fullReviewAction->execute($sophie, $post->id, "Technical analysis of the SPA routing system.", [
            [
                'snippet_id' => $snippet->id,
                'content' => "// Optimized SPA code\nLivewire.navigate('/dashboard');",
                'description' => 'Using direct navigate for performance.'
            ]
        ]);

        // -- ACTION 5: Julie reacts 'Elegant' to Enrique's post --
        $reactionAction->execute($julie, $post, 'elegant');

        echo "🚀 All 'Real' notifications seeded using Actions!\n";
    }
}
