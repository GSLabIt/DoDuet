<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    //SOCIALITE PROVIDERS
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI')
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI')
    ],
    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_REDIRECT_URI')
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI')
    ],
    'microsoft' => [
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
        'redirect' => env('MICROSOFT_REDIRECT_URI')
    ],
    'wordpress' => [
        'client_id' => env('WORDPRESS_CLIENT_ID'),
        'client_secret' => env('WORDPRESS_CLIENT_SECRET'),
        'redirect' => env('WORDPRESS_REDIRECT_URI')
    ],
    'yahoo' => [
        'client_id' => env('YAHOO_CLIENT_ID'),
        'client_secret' => env('YAHOO_CLIENT_SECRET'),
        'redirect' => env('YAHOO_REDIRECT_URI')
    ],
    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI'),
    ],
    'twitch' => [
        'client_id' => env('TWITCH_CLIENT_ID'),
        'client_secret' => env('TWITCH_CLIENT_SECRET'),
        'redirect' => env('TWITCH_REDIRECT_URI')
    ],
    'asana' => [
        'client_id' => env('ASANA_CLIENT_ID'),
        'client_secret' => env('ASANA_CLIENT_SECRET'),
        'redirect' => env('ASANA_REDIRECT_URI')
    ],
    'atlassian' => [
        'client_id' => env('ATLASSIAN_CLIENT_ID'),
        'client_secret' => env('ATLASSIAN_CLIENT_SECRET'),
        'redirect' => env('ATLASSIAN_REDIRECT_URI')
    ],
    'bitly' => [
        'client_id' => env('BITLY_CLIENT_ID'),
        'client_secret' => env('BITLY_CLIENT_SECRET'),
        'redirect' => env('BITLY_REDIRECT_URI')
    ],
    'bitbucket' => [
        'client_id' => env('BITBUCKET_CLIENT_ID'),
        'client_secret' => env('BITBUCKET_CLIENT_SECRET'),
        'redirect' => env('BITBUCKET_REDIRECT_URI')
    ],
    'digitalocean' => [
        'client_id' => env('DIGITALOCEAN_CLIENT_ID'),
        'client_secret' => env('DIGITALOCEAN_CLIENT_SECRET'),
        'redirect' => env('DIGITALOCEAN_REDIRECT_URI')
    ],
    'dropbox' => [
        'client_id' => env('DROPBOX_CLIENT_ID'),
        'client_secret' => env('DROPBOX_CLIENT_SECRET'),
        'redirect' => env('DROPBOX_REDIRECT_URI')
    ],
    'envato' => [
        'client_id' => env('ENVATO_CLIENT_ID'),
        'client_secret' => env('ENVATO_CLIENT_SECRET'),
        'redirect' => env('ENVATO_REDIRECT_URI')
    ],
    'eventbrite' => [
        'client_id' => env('EVENTBRITE_CLIENT_ID'),
        'client_secret' => env('EVENTBRITE_CLIENT_SECRET'),
        'redirect' => env('EVENTBRITE_REDIRECT_URI')
    ],
    'heroku' => [
        'client_id' => env('HEROKU_CLIENT_ID'),
        'client_secret' => env('HEROKU_CLIENT_SECRET'),
        'redirect' => env('HEROKU_REDIRECT_URI')
    ],

];
