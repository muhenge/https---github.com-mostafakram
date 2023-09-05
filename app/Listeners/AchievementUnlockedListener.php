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

        $user = $event->user;
        $achievement_type = $event->type;

        $existingAchievement = DB::table((new Achievement)->getTable())
            ->where('user_id', $user->id)
            ->where('achievement_type', $achievement_type)
            ->first();

        if ($existingAchievement) {
            DB::table((new Achievement)->getTable())
                ->where('user_id', $user->id)
                ->where('achievement_type', $achievement_type)
                ->update([
                    'unlocked_achievement' => $event->achievement_name,
                ]);
        } else {
            DB::table((new Achievement)->getTable())->insert([
                'unlocked_achievement' => $event->achievement_name,
                'user_id' => $user->id,
                'achievement_type' => $achievement_type,
            ]);
        }
    }
}
