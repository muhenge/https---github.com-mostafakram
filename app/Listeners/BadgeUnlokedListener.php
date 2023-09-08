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
        $badge_name = $event->badge_name;
        $user_achievements = Achievement::where('user_id', $user->id)->count();
        $user_achievements = $event->badge_name;

        $badge = match (true) {
            $user_achievements >= 10 => 'Master',
            $user_achievements >= 8 => 'Advanced',
            $user_achievements >= 4 => 'Intermediate',
            $user_achievements <= 4 => 'Beginner'
        };

        $badge_name = $badge;
        $badge_init = new Badge();

        $found = $badge_init::findByBadgeNameAndUserId($badge_name, $user->id);
        if($found) {
            if ($foude->name !== $badge_name) {
                $found->update(['name' => $badge_name]);
            }
        } else {
            Badge::create([
                'user_id' => $user->id,
                'name' => $badge_name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Badge::where('user_id', $user->id)
        ->where('name', '!=', $badge_name)
        ->delete();
    }
}
