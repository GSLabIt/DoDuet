# Settings module

This module is responsible for the definition and modification of multiple settings using highly customizable and
strongly typed DTOs.

## Installation

In order to start the installation of this module, require this in your `composer.json`:
```json5
{
    // ...
    "repositories": [
        // ...
        {
            "type": "vcs",
            "url": "git@github.com:Do-inc/laravel-settings-module.git"
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
php artisan module:install do-inc/settings-module ^1.0
```

Eventually consider publishing configuration files and migrations:
```bash
php artisan module:publish-config
```

## Setup
### Mandatory steps
* Apply the `HasSettings` trait to your `User` model

### Optional steps
* Register one or more setting in your seeder calling the `Settings` facade
* Extend the available DTOs creating a class that inherit the `Setting` base DTO, all the parsing process will be done
  automatically. For extended DTOs usage check [Spatie's DTO](https://github.com/spatie/data-transfer-object). Note that
  the extended version of DTOs is used so that each DTO can also be used as a cast, further explaination 
  [here](https://github.com/jessarcher/laravel-castable-data-transfer-object).


This package exposes an easy to use `settings` helper for faster and easier usage
