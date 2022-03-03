# Referral module

This module is responsible for the handling of referral codes and invitation.

Referral redemption handling is done via the event `ReferralRedeemed` fired once an user redeems the
prize for one or more referrals.

The event is fired once per redeem, multiple redeem will result in the event being fired multiple times.

## Installation

In order to start the installation of this module, require this in your `composer.json`:
```json5
{
  // ...
  "repositories": [
    // ...
    {
      "type": "vcs",
      "url": "git@github.com:Do-inc/laravel-module-referral.git"
    }
  ],
  "require": {
    // ...
    "do-inc/referral-module": "^1.0",
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
php artisan module:install do-inc/referral-module
```

Eventually consider publishing configuration files and migrations:
```bash
php artisan module:publish-config
php artisan module:publish-migration
```

## Setup
### Mandatory steps
* Apply the `Referrable` trait to your `User` model

### Optional steps
* Listen to the `ReferralRedeemed` event
* Listen to the `NewReferralReceived` event
* Call `Doinc\Modules\Referral\Facades\Referral::check()` somewhere you want to check for a referral code insertion, 
usually this is done during registration.

## Routes
All routes gets prefixed by `/referral`

| Method | Name                                            | Callback                           | Route                   |
|--------|-------------------------------------------------|------------------------------------|-------------------------|
| `Get`  | `authenticated.referral.render.index`           | `ReferralController@index`         | `/`                     |
| `Get`  | `authenticated.referral.get.url`                | `ReferralController@url`           | `/url`                  |
| `Get`  | `authenticated.referral.get.new_ref_prize`      | `ReferralController@newRefPrize`   | `/prize`                |
| `Get`  | `authenticated.referral.get.total_ref_prize`    | `ReferralController@totalRefPrize` | `/total-prize`          |
|        |                                                 |                                    |                         |
| `Post` | `public.referral.post.store`                    | `ReferralController@store`         | `/`                     |
| `Post` | `authenticated.referral.post.redeem_all_prizes` | `ReferralController@redeemAll`     | `/redeem`               |
| `Post` | `authenticated.referral.post.redeem_prize`      | `ReferralController@redeem`        | `/redeem/{referred_id}` |

## Views
Views are not compiled but only generated as a placeholder, feel free to edit them as needed.

## Events
This module fires the following events:
* `ReferralRedeemed`: Fired after a successful redemption of one or more prizes, internally fired accessing:
  * `authenticated.referral.post.redeem_all_prizes`
  * `authenticated.referral.post.redeem_prize`
* `NewReferralReceived`: Fired calling `Doinc\Modules\Referral\Facades\Referral::check()`