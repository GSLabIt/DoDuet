# Settings module

This module is responsible for ________________________.

## Installation

In order to start the installation of this module, require this in your `composer.json`:
```json5
{
  // ...
  "repositories": [
    // ...
    {
      "type": "vcs",
      "url": "git@github.com:Do-inc/laravel-module-settings.git"
    }
  ],
  "require": {
    // ...
    "do-inc/settings-module": "^1.0",
    // ...
  },
  "extra": {
    // ...
    "module-dir": "modules"
  },
}
```

And install it updating composer's packages:
```bash
composer update
```

If a standard package installation is what you want you can stop here, otherwise if installing as a laravel module,
ensure you have installed the _Laravel Module Installer_, if not run:
```bash
composer require joshbrw/laravel-module-installer
```

Finally install the module running:
```bash
php artisan module:install do-inc/settings-module
```

Eventually consider publishing configuration files and migrations:
```bash
php artisan module:publish-config
php artisan module:publish-migration
```

## Setup
### Mandatory steps
* Implement the `____` interface to your `User` model
* Apply the `____` trait to your `User` model

### Optional steps
* Listen to the `____` event
* Call `____` somewhere you want to check for a referral code insertion, usually this is done during registration.

## Routes
All routes gets prefixed by `/settings`

| Method | Name                                            | Callback                           | Route                   |
|--------|-------------------------------------------------|------------------------------------|-------------------------|
| `Get`  | `authenticated.settings.render.index`       | `SettingsController@index`    | `/`                     |
|        |                                                 |                                    |                         |
| `Post` | `public.settings.post.store`                | `SettingsController@store`    | `/`                     |
| `Post` | `authenticated.settings.post.example`       | `ReferralController@redeemAll`     | `/example`              |

## Views
Views are not compiled but only generated as a placeholder, feel free to edit them as needed.

## Events
This module fires the following events:
* `____`: Fired after a successful redemption of one or more prizes, internally fired accessing:
  * `authenticated.settings.post.example`
* `____`: Fired calling `____`