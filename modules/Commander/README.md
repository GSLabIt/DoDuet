# Commander module

This module is responsible for the creation and overwriting of the laravel module default commands.

## Installation

In order to start the installation of this module, require this in your `composer.json`:
```json5
{
    // ...
    "repositories": [
        // ...
        {
            "type": "vcs",
            "url": "git@github.com:Do-inc/laravel-commander-module.git"
        }
    ],
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
php artisan module:install do-inc/commander ^1.0
```

## Setup
### Mandatory steps
* Run the artisan command `commander:self-install` 
