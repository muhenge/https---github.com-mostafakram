<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlockedEvent;
use Illuminate\Support\Facades\DB;
use App\Models\Achievement;
use Illuminate\Support\Carbon;

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



        $existingAchievement = DB::table('achievements')
        ->where('user_id', $user->id)
        ->where('achievement_type', $achievement_type)
        ->first();

        if ($existingAchievement) {
            if ($existingAchievement->unlocked_achievement !== $event->achievement_name) {
                DB::table('achievements')
                ->where('id', $existingAchievement->id)
                    ->update([
                        'unlocked_achievement' => $event->achievement_name,
                        'counter' => $existingAchievement->counter + 1,
                        'updated_at' => Carbon::now()
                    ]);
            }
        } else {
            DB::table('achievements')->insert([
                'unlocked_achievement' => $event->achievement_name,
                'user_id' => $user->id,
                'achievement_type' => $achievement_type,
                'counter' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $user_achievement_count = Achievement::where('user_id', $user->id)->sum('counter');
        event(new BadgeUnlockedEvent($user_achievement_count, $user));
    }
}
