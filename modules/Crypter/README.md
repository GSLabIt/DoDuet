# Crypter module

This module is responsible for the wrapping of many functionalities around the libsodium cryptographic library.

## Installation

In order to start the installation of this module, require this in your `composer.json`:
```json5
{
    // ...
    "repositories": [
        // ...
        {
            "type": "vcs",
            "url": "git@github.com:Do-inc/laravel-crypter-module.git"
        }
    ],
    "require": {
        // ...
        "do-inc/crypter-module": "^1.0",
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

In order to complete the installation ensure you have installed the _Laravel Module Installer_, if not run:
```bash
composer require joshbrw/laravel-module-installer
```

Finally install the module running:
```bash
php artisan module:install do-inc/crypter-module ^1.0
```

Eventually consider publishing configuration files :
```bash
php artisan module:publish-config
```

## Setup
### Mandatory steps
* Generate the encryption keys running `php artisan crypter:gen-key`
* Generate the appropriate migration columns using `$table::encrypted(...)` inside your migration
* Apply the `Encrypted` trait to all your modules that needs to use encryption
* Apply `SodiumEncrypted`/`JSONSodiumEncrypted` cast to the encrypted properties of your model
* Add the `APP_SYMMETRIC_KEY` value to your environment, the key