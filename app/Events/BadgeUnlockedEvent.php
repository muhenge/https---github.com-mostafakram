<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlockedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $badge_name;
    public $user;

    public function __construct($badge_name, $user)
    {
        $this->badge_name = $badge_name;
        $this->user = $user;
    }

}
