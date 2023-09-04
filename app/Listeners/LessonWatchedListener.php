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
        $lesson = $event->lesson;
        $user = $event->user;

        $lessons = LessonUser::where('user_id', $user->id)->get()->count();

        $watched = match (true) {
            $lessons >= 25 => LessonUser::TWENTY_FIVE_WATCHED,
            $lessons >= 20 => LessonUser::TWENTY_WATCHED,
            $lessons >= 10 => LessonUser::TEN__WATCHED,
            $lessons >= 5 => LessonUser::FIVE_WATCHED,
            $lessons < 3 => LessonUser::FIRST_WATCHED
        };

        event(new AchievementUnlocked($watched, $user));

    }
}
