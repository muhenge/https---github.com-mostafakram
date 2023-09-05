<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlockedEvent;
use App\Models\Achievement;
use App\Models\Badge;
use Faker\Provider\Base;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class BadgeUnlokedListener
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
    public function handle(BadgeUnlockedEvent $event)
    {
        $user = $event->user;

        $user_achievements = Achievement::where('user_id', $user->id)->count();
        $user_achievements = $event->badge_name;

        $badge = match (true) {
            $user_achievements >= 10 => 'Master',
            $user_achievements >= 8 => 'Advanced',
            $user_achievements >= 4 => 'Intermediate',
            $user_achievements <= 4 => 'Beginner'
        };


        $existing = DB::table((new Badge())->getTable())
            ->where('user_id', $user->id)
            ->where('name', $badge)
            ->first();
        if($existing) {
            DB::table(new Badge())->getTable()
            ->where('user_id', $user->id)
            ->update([
                'name' => $badge
            ]);
        } else {
            DB::table((new Badge())->getTable())->insert([
                'user_id' => $user->id,
                'name' => $badge,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }


    }
}
