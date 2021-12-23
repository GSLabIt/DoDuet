<?php

namespace App\Providers;

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

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\GitHub\GitHubExtendSocialite::class.'@handle',
            \SocialiteProviders\Google\GoogleExtendSocialite::class.'@handle',
            \SocialiteProviders\Twitter\TwitterExtendSocialite::class.'@handle',
            \SocialiteProviders\Facebook\FacebookExtendSocialite::class.'@handle',
            \SocialiteProviders\Microsoft\MicrosoftExtendSocialite::class.'@handle',
            \SocialiteProviders\WordPress\WordPressExtendSocialite::class.'@handle',
            \SocialiteProviders\Yahoo\YahooExtendSocialite::class.'@handle',
            \SocialiteProviders\Discord\DiscordExtendSocialite::class.'@handle',
            \SocialiteProviders\Twitch\TwitchExtendSocialite::class.'@handle',
            \SocialiteProviders\Asana\AsanaExtendSocialite::class.'@handle',
            \SocialiteProviders\Atlassian\AtlassianExtendSocialite::class.'@handle',
            \SocialiteProviders\Bitly\BitlyExtendSocialite::class.'@handle',
            \SocialiteProviders\Bitbucket\BitbucketExtendSocialite::class.'@handle',
            \SocialiteProviders\DigitalOcean\DigitalOceanExtendSocialite::class.'@handle',
            \SocialiteProviders\Dropbox\DropboxExtendSocialite::class.'@handle',
            \SocialiteProviders\Envato\EnvatoExtendSocialite::class.'@handle',
            \SocialiteProviders\Eventbrite\EventbriteExtendSocialite::class.'@handle',
            \SocialiteProviders\Heroku\HerokuExtendSocialite::class.'@handle',
        ],
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
