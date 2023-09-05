<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AchievementUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $achievement_name;
    public $user;
    public $type;


    public function __construct(string $achievement_name, User $user, $type)
    {
        $this->achievement_name = $achievement_name;
        $this->user = $user;
        $this->type = $type;
    }
}
