# Settings module

This module is responsible for ___.

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
    }
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
* Apply the `___` trait to your `User` model

### Optional steps
* Listen to the `___` event
* Call `___` somewhere you want to ___.

## Routes
All routes gets prefixed by `/settings`

| Method | Name                                            | Callback                           | Route                   |
|--------|-------------------------------------------------|------------------------------------|-------------------------|
| `Get`  | `authenticated.settings.render.index`         | `ReferralController@index`         | `/`                     |
| `Get`  | `authenticated.settings.get.sample`           | `ReferralController@sample`        | `/sample`               |
|        |                                                 |                                    |                         |
| `Post` | `public.settings.post.sample`                 | `ReferralController@sample`        | `/sample`               |
| `Post` | `authenticated.settings.post.sample`          | `ReferralController@redeemAll`     | `/redeem`               |

## Views
Views are not compiled but only generated as a placeholder, feel free to edit them as needed.

## Events
This module fires the following events:
* `___`: Fired after a successful redemption of one or more prizes, internally fired accessing:
    * `___`
* `___`: Fired calling `___`
