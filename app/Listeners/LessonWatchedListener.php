<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Lesson;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LessonUser;

class LessonWatchedListener
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
    public function handle(LessonWatched $event): void
    {
        $user = $event->user;

        $lessons = LessonUser::where('user_id', $user->id)
        ->where('watched', true)
        ->count();

        $watched = match (true) {
            $lessons >= 25 => LessonUser::TWENTY_FIVE_WATCHED,
            $lessons >= 20 => LessonUser::TWENTY_WATCHED,
            $lessons >= 10 => LessonUser::TEN__WATCHED,
            $lessons >= 5 => LessonUser::FIVE_WATCHED,
            $lessons < 5 => LessonUser::FIRST_WATCHED
        };
        $achievementType= 'lesson_watched';
        event(new AchievementUnlocked($watched, $user, $achievementType));
    }
}
