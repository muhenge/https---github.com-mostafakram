<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Comment;

class CommentWrittenListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $comments = Comment::where('user_id', $comment->user->id)->get()->count();
        $achievement = match (true) {
            $comments >= 20 => Comment::TWENTY_COMMENTS,
            $comments >= 10 => Comment::TEN_COMMENTS,
            $comments >= 5 => Comment::FIVE_COMMENTS,
            $comments >= 3 => Comment::THREE_COMMENTS,
            $comments < 3 => Comment::FIRST_COMMENT,
        };

        $user = User::find($event->comment->user_id);
        $achievementType = 'comment_written';
        event(new AchievementUnlocked($achievement, $user, $achievementType));
    }
}
