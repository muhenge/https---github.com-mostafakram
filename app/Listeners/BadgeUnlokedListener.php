<?php

namespace App\Listeners;

use App\Events\BadgeUnlockedEvent;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    public function handle(BadgeUnlockedEvent $event): string
    {
        $name = $event->badge_name;
        $user = $event->user;

        $user_achievements = Achievement::where('user_id', $user->id)->count();

        $badges = match(true) {
            $user_achievements >= 10 => 'Master',
            $user_achievements >= 8 => 'Advanced',
            $user_achievements >=4 => 'Intermediate',
            $user_achievements <= 4 => 'Beginner'
        };

        return $badges;
    }
}
