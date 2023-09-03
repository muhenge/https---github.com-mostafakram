<?php

namespace App\Providers;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\BadgeUnlokedListener;
use App\Listeners\CommentWrittenAchievement;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\BadgeUnlokedEvent;
use App\Listeners\AchievementUnlockedListener;
use App\Listeners\CommentWrittenListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AchievementUnlocked::class => [
            AchievementUnlockedListener::class,
        ],
        BadgeUnlokedEvent::class => [
            BadgeUnlokedListener::class
        ],

        CommentWritten::class => [
            CommentWrittenListener::class
        ]
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
