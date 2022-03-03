{
    "name": "do-inc/{{$kebab}}-module",
    "description": "___",
    "version": "1.0.0",
    "type": "laravel-module",
    "keywords": [
        "do-inc",
        "laravel",
        "{{$snake}}",
        "module"
    ],
    "authors": [
        {
            "name": "Emanuele (ebalo) Balsamo",
            "email": "emanuele.balsamo@do-inc.co"
        }
    ],
    "extra": {
        "laravel": {
            "providers": [
                "{{$escaped_namespace}}\\Providers\\{{$studly}}ServiceProvider"
            ],
            "aliases": {
                "{{$studly}}": "{{$escaped_namespace}}\\Facades\\{{$studly}}"
            }
        }
    },
    "autoload": {
        "psr-4": {
            "{{$escaped_namespace}}\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "{{$escaped_namespace}}\\Tests\\": "tests/"
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "phpstan/extension-installer": true
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true,
    "require": {
        "php": "^8.1",
        "guzzlehttp/guzzle": "^7.2",
        "illuminate/contracts": "^9.0",
        "inertiajs/inertia-laravel": "^0.5.4",
        "nwidart/laravel-modules": "^9.0",
        "spatie/laravel-activitylog": "^4.4"
    },
    "require-dev": {
        "nunomaduro/collision": "^6.0",
        "nunomaduro/larastan": "^2.0.1",
        "orchestra/testbench": "^7.0",
        "pestphp/pest": "^1.21",
        "pestphp/pest-plugin-laravel": "^1.1",
        "phpstan/extension-installer": "^1.1",
        "phpstan/phpstan-deprecation-rules": "^1.0",
        "phpstan/phpstan-phpunit": "^1.0",
        "phpunit/phpunit": "^9.5",
        "spatie/laravel-ray": "^1.26"
    }
}
