# API Smarty Street module

This module is responsible for the usage of the Smarty Street API service.

**THIS MODULE IS YET UNCOMPLETED**, 
only the functionalities for the international address verification have been defined.

API service reference:
[smarty.com](https://www.smarty.com/)

## Installation

In order to start the installation of this module, require this in your `composer.json`:
```json5
{
    // ...
    "repositories": [
        // ...
        {
            "type": "vcs",
            "url": "git@github.com:Do-inc/laravel-smarty-street-api-module.git"
        }
    ],
    "require": {
        // ...
        "do-inc/smarty-street-api-module": "^1.0",
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
php artisan module:install do-inc/smarty-street-api-module
```

Eventually consider publishing configuration files and migrations:
```bash
php artisan module:publish-config
```

## Setup
### Mandatory steps
* None

### Optional steps
* Use the `SmartyStreetApi` facade to access the module functionalities and verification

## Views
Views are not compiled but only generated as a placeholder, feel free to edit them as needed.

## Events
None
