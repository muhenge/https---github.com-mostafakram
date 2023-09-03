<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Events\CommentWritten;

class AchievementUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $achievement_name;
    public $user;



    public function __construct(string $achievement_name, User $user)
    {
        $this->achievement_name = $achievement_name;
        $this->user = $user;
    }
}
