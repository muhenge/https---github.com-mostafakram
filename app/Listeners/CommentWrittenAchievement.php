<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Comment;
use App\Services\CommentAchivementService;
use Illuminate\Support\Facades\Event;
use App\Events\AchievementUnlocked;

class CommentWrittenAchievement
{
    protected $achievementService;

    public function __construct(CommentAchivementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }


    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $user = $comment->user;
        $achievementLevel = Comment::where('user_id', $comment->user->id)->get()->count();
        $achievementsNames = $this->achievementService->getAchievementName($achievementLevel);
        Event::dispatch(new AchievementUnlocked($achievementsNames, $user));
    }
}
