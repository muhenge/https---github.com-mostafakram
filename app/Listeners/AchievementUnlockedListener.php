<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use App\Models\Achievement;

class AchievementUnlockedListener
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
    public function handle(AchievementUnlocked $event): void
    {
       DB::table((new Achievement)->getTable())->insert([
           'unlocked_achievement' => $event->achievement_name,
           'user_id' => $event->user->id
       ]
       );
    }
}
