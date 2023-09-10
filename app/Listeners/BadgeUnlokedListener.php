<?php

namespace App\Listeners;

use App\Events\BadgeUnlockedEvent;
use App\Models\Achievement;
use App\Models\Badge;



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

        $user_achievement_count = Achievement::where('user_id', $user->id)->sum('counter');

        $badge = match (true) {
            $user_achievement_count >= 10 => 'Master',
            $user_achievement_count >= 8 => 'Advanced',
            $user_achievement_count >= 4 => 'Intermediate',
            $user_achievement_count <= 4 => 'Beginner'
        };

        $badge_name = $badge;
        $badge_init = new Badge();

        $found = $badge_init::findByBadgeNameAndUserId($badge_name, $user->id);
        if($found) {
            if ($found->name !== $badge_name) {
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
