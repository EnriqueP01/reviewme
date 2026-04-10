<?php

namespace Tests\Feature;

use App\Actions\Comments\StorePostCommentAction;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralNotification;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_actions_trigger_notifications_to_owners()
    {
        // 1. Arrange
        $author = User::factory()->create();
        $visitor = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        Notification::fake();

        // 2. Act (Comment on post)
        $action = app(StorePostCommentAction::class);
        $action->execute($visitor, $post->id, 'This is a test comment');

        // 3. Assert
        Notification::assertSentTo(
            $author,
            GeneralNotification::class,
            function ($notification, $channels) use ($visitor) {
                return $notification->type === 'comment' && str_contains($notification->message, $visitor->name);
            }
        );
    }

    public function test_replies_trigger_notifications_to_comment_author()
    {
        // 1. Arrange
        $postAuthor = User::factory()->create();
        $commentAuthor = User::factory()->create();
        $replier = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postAuthor->id]);
        
        $action = app(StorePostCommentAction::class);
        $comment = $action->execute($commentAuthor, $post->id, 'Original comment');

        Notification::fake();

        // 2. Act (Reply to comment)
        $action->execute($replier, $post->id, 'This is a reply', $comment->id);

        // 3. Assert
        Notification::assertSentTo(
            $commentAuthor,
            GeneralNotification::class,
            function ($notification) {
                return $notification->title === __('New Reply');
            }
        );
    }
}
