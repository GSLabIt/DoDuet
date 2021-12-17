<?php

namespace App\Providers;

use App\Events\onAfterKeyRotation;
use App\Events\onBeforeKeyRotation;
use App\Listeners\onAfterKeyRotationWalletListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        onBeforeKeyRotation::class => [],
        onAfterKeyRotation::class => [
            onAfterKeyRotationWalletListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
