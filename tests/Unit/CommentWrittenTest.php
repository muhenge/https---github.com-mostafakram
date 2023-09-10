<?php

namespace Tests\Unit;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\CommentWrittenListener;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\TestCase;
use App\Models\Comment;



class CommentWrittenTest extends TestCase
{

    /**
     * @test
     */
    public function it_unlock_10_comment_written_achievement()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
        ]);

        $commentEvent = new CommentWritten($comment);

        $listener = new CommentWrittenListener();

        $listener->handle($commentEvent);

        Event::assertDispatched(CommentWritten::class, function ($event) use ($user) {
            return $event->comment->user_id === $user->id;
        });

    }
}

