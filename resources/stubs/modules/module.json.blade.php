{
    "name": "{{$kebab}}",
    "alias": "{{$kebab}}-module",
    "description": "___",
    "keywords": [
        "do-inc",
        "laravel",
        "{{$snake}}",
        "module"
    ],
    "priority": 0,
    "providers": [
        "{{$escaped_namespace}}\\Providers\\{{$studly}}ServiceProvider"
    ],
    "aliases": {
        "{{$studly}}": "{{$escaped_namespace}}\\Facades\\{{$studly}}"
    },
    "files": [],
    "requires": [],
    "minimumCoreVersion": "^9.0",
    "title": "{{$studly}} module",
    "version": "1.0.0",
    "author": "Emanuele (ebalo) Balsamo"
}
