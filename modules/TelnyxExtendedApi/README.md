# Telnyx Extended Api module

This module is responsible for the extension of some of the functionalities of the Telnyx SDK that seems not to be too 
up to date.

## Installation

In order to start the installation of this module, require this in your `composer.json`:
```json5
{
    // ...
    "repositories": [
        // ...
        {
            "type": "vcs",
            "url": "git@github.com:Do-inc/laravel-telnyx-extended-api-module.git"
        }
    ],
    "extra": {
        // ...
        "module-dir": "modules"
    }
}
```

If a standard package installation is what you want you can stop here, otherwise if installing as a laravel module,
ensure you have installed the _Laravel Module Installer_, if not run:
```bash
composer require joshbrw/laravel-module-installer
```

Finally install the module running:
```bash
php artisan module:install do-inc/telnyx-extended-api-module ^1.0
```

Eventually consider publishing configuration files and migrations:
```bash
php artisan module:publish-config
```

## Setup
### Mandatory steps
* None

### Optional steps
* Listen to the `NumberLookup` event

## Routes
No routes given at the moment

## Views
Views are not compiled but only generated as a placeholder, feel free to edit them as needed.

## Events
This module fires the following events:
* `NumberLookup`: Fired after a successful phone number lookup
